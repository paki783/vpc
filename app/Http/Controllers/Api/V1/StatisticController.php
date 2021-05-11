<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Statistic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class StatisticController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;
    
    // winByManager
    // public function winByManager(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($request->all(), [
    //         "match_id" => "required|exists:matches,id",
    //         "team_id" => "required|exists:teams,id",
    //         "home_score" => "required|numeric",
    //         "away_score" => "required|numeric",
    //         "image" => "max:2048",
    //         "video_url" => "required",
    //     ]);
    //     if ($validator->fails()) {
    //         $errors[] = $validator->errors();
    //         return Helper::errorResponse(422, $validator->errors()->all());
    //     }

    //     DB::beginTransaction();
    //     try {

    //         if (!(Auth::user()->hasRole(['assistant','manager']))) {
    //             return Helper::errorResponse(422, ['Role Doesn\'t exist or missing access rights to application.']);
    //         }

    //         $lineUp = LineUp::where('id', $input['match_id'])->first();
    //         if (isset($lineUp) && count($lineUp) > 0) {
    //             return Helper::errorResponse(422, ['This is no line-up of this match.']);
    //         }

    //         $matchUpdate = Match::where('id', $input['match_id'])->update([
    //             'home_score' => $input['home_score'],
    //             'away_score' => $input['away_score'],
    //             'match_status' => 'in progress',
    //         ]);
            
    //         $matchScore = MatchScore::firstOrCreate([
    //             'user_id' => Auth::user()->id,
    //             'match_id' => $input['match_id'],
    //             'team_id' => $input['team_id'],
    //             'home_score' => $input['home_score'],
    //             'away_score' => $input['away_score'],
    //             'video_url' => $input['video_url'],
    //         ]);

    //         // Save Photo
    //         if (@$request->hasFile('image')) {
    //             $image = $request->file('image');
    //             $file = $image;
    //             $current_image = $matchScore->image;
    //             // Remove Photo
    //             if (isset($current_image) && @$current_image != null) {
    //                 $pathToRemove =  storage_path('app/public') . $this->image_upload_dir . '/' . $current_image;
    //                 Helper::deleteFile($pathToRemove);
    //             }
    //             // Save Photo

    //             $uploads_dir = storage_path('app/public').$this->image_upload_dir;
    //             $save_image = Helper::uploadFile($file, $uploads_dir);

    //             if ($save_image) {
    //                 $matchScoreUpdate = MatchScore::where('id', $matchScore->id)
    //                 ->update(['image' => $save_image]);
    //                 $matchScore = MatchScore::find($matchScore->id);
    //             }
    //         }
            
    //         $data = array(
    //             'matchScore' => $matchScore
    //         );

    //         DB::commit();
    //         return Helper::successResponse($data, 'Successfully Update Record.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return Helper::errorResponse($e->getCode(), $e->getMessage());
    //     }
    // }

    // getGameStatistic
    public function getGameStatistic(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "game_id" => "required|exists:games,id",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $statistic = Statistic::where("game_id", $input['game_id'])->get();

            
            // data
            $data = array(
                'statistic' => $statistic
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
}
