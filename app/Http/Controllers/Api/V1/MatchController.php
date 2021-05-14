<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\LineUp;
use App\Match\MatchScore;
use App\Match;
use App\PlayerPosition;
use App\User\UserAssistant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class MatchController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    public function __construct()
    {
        $this->image_upload_dir = "/match";
    }

    // winByManager
    public function winByManager(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match_id" => "required|exists:matches,id",
            "team_id" => "required|exists:teams,id",
            "home_score" => "required|numeric",
            "away_score" => "required|numeric",
            "image" => "max:2048",
            "video_url" => "required",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        DB::beginTransaction();
        try {

            if (!(Auth::user()->hasRole(['assistant','manager']))) {
                return Helper::errorResponse(422, ['Role Doesn\'t exist or missing access rights to application.']);
            }

            $lineUp = LineUp::where('match_id', $input['match_id'])->first();
            if (isset($lineUp) && count($lineUp) > 0) {
                return Helper::errorResponse(422, ['This is no line-up of this match.']);
            }

            $getMatchScore = MatchScore::where([
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id'],
            ]);

            if ($getMatchScore->exists()) {
                $getMatchScore = $getMatchScore->first();
                
                $status = 'in progress';
                if ($getMatchScore['user_id'] != Auth::user()->id) {
                    if((@$getMatchScore['home_score'] == $input['home_score']) &&
                    (@$getMatchScore['away_score'] == $input['away_score'])){
                        $status = 'completed';
                    }else{
                        $status = 'disputed';
                    }
                }

                $matchUpdate = Match::where('id', $input['match_id'])->update([
                    'home_score' => $input['home_score'],
                    'away_score' => $input['away_score'],
                    'match_status' => $status,
                ]);

            }else{
                $matchUpdate = Match::where('id', $input['match_id'])->update([
                    'home_score' => $input['home_score'],
                    'away_score' => $input['away_score'],
                    'match_status' => 'in progress',
                ]);
            }

            $matchScore = MatchScore::updateOrCreate([
                'user_id' => Auth::user()->id,
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id']
            ],
            [
                'user_id' => Auth::user()->id,
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id'],
                'home_score' => $input['home_score'],
                'away_score' => $input['away_score'],
                'video_url' => $input['video_url'],
            ]);

            // Save Photo
            if (@$request->hasFile('image')) {
                $image = $request->file('image');
                $file = $image;
                $current_image = $matchScore->image;
                // Remove Photo
                if (isset($current_image) && @$current_image != null) {
                    $pathToRemove =  storage_path('app/public') . $this->image_upload_dir . '/' . $current_image;
                    Helper::deleteFile($pathToRemove);
                }
                // Save Photo

                $uploads_dir = storage_path('app/public').$this->image_upload_dir;
                $save_image = Helper::uploadFile($file, $uploads_dir);

                if ($save_image) {
                    $matchScoreUpdate = MatchScore::where('id', $matchScore->id)
                    ->update(['image' => $save_image]);
                    $matchScore = MatchScore::find($matchScore->id);
                }
            }
            
            $data = array(
                'matchScore' => $matchScore
            );

            DB::commit();
            return Helper::successResponse($data, 'Successfully Update Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // checkMatchResult
    public function checkMatchResult(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "match_id" => "required|exists:matches,id",
            "team_id" => "required|exists:teams,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        DB::beginTransaction();
        try {

            if (!(Auth::user()->hasRole(['assistant','manager']))) {
                return Helper::errorResponse(422, ['Role Doesn\'t exist or missing access rights to application.']);
            }

            $matchScore = MatchScore::where([
                'match_id' => $input['match_id'],
                'team_id' => $input['team_id']
            ])
            ->first();

            $data = array(
                'matchScore' => $matchScore
            );

            DB::commit();
            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    // getTeamMatch
    public function getTeamMatch(Request $request){
        $input = $request->all();
        try {
            
            $match = Match::with([
                "getTeamOne",
                "getTeamTwo",
                "getLeague"
            ])
            ->where([
                'league_id' => $input['league_id'],
                'division_id' => $input['division_id'],
            ])
            ->whereRaw("(team_one_id = ".$input['team_id']." OR team_two_id = ".$input['team_id'].")");

            $this->paginate = true;
            if (isset($input['perPage']) && $input['perPage'] != "") {
                $match = $match->paginate($input['perPage']);
            } else {
                $match = $match->paginate($this->noOfRecordPerPage);
            }

            // data
            return Helper::successResponse($match, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
}
