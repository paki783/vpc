<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Notification;
use Illuminate\Support\Facades\Auth;
use Validator;

class NotificationController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;

    public function unreadCount(Request $request)
    {
        $input = $request->all();
        try {
            $user = auth()->user();

            $notification = Notification::where('user_id',$user->id)->where('is_read', 0)->count();

            $data = array(
                'notification' => $notification
            );

            return Helper::successResponse($data, 'Successfully Get Record.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $input = $request->all();
        try {
            $user = auth()->user();
            
            $notificationUpdate = Notification::where('user_id',$user->id)->update(['is_read' => 1]);

            $notification = Notification::where('user_id',$user->id)->orderBy('id', 'DESC');

            $this->paginate = true;
            if (isset($input['perPage']) && $input['perPage'] != "") {
                $notification = $notification->paginate($input['perPage']);
            } else {
                $notification = $notification->paginate($this->noOfRecordPerPage);
            }

            return Helper::successResponse($notification, 'Successfully Get Record.',$this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

}
