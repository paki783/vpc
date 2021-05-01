<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class DeviceController extends Controller
{
    private $noOfRecordPerPage = 10;
    private $paginate = false;
    /**
     * Store a newly created User in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "uuid" => "required",
            "token" => "required",
            "type" => "required",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422,$validator->errors()->all());
        }

        try {
            $deviceData = [
                "user_id" => $input['user_id'],
                "uuid" => $input['uuid'],
                "token" => $input['token'],
                "type" => $input['type'],
            ];
            
            $device = Device::create($deviceData);
            $data = array(
                "device" => $device
            );
            return Helper::successResponse($data, "Successfully Created.");
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "id" => "required|exists:devices,id",
            "user_id" => "required",
            "uuid" => "required",
            "token" => "required",
            "type" => "required",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {
            $deviceData = [
                "user_id" => $input['user_id'],
                "uuid" => $input['uuid'],
                "token" => $input['token'],
                "type" => $input['type'],
            ];

            $device = Device::find($input['id']);
            $device->update($deviceData);

            $data = array(
                "device" => $device
            );
            return Helper::successResponse($data, 'Successfully Record Updated.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    
    public function list(Request $request)
    {
        $input = $request->only('search_value', 'search_by', 'page', 'pagination', 'perPage', 'device_id');
        try {
            $device = new device();
            // search
            if ((isset($input['search_by']) && $input['search_by'] != "") && (isset($input['search_value']) && $input['search_value'] != "")) {
                $device = $device->where($input['search_by'], $input['search_value']);
            }

            // pagination and find
            if (isset($input['pagination']) && $input['pagination'] != "") {
                $this->paginate = true;
                if (isset($input['perPage']) && $input['perPage'] != "") {
                    $device = $device->paginate($input['perPage']);
                } else {
                    $device = $device->paginate($this->noOfRecordPerPage);
                }
            } else {
                $device_id = $input['device_id'];
                $device = $device->find($device_id);
            }
            // data
            return Helper::successResponse($device, 'Successfully Get Record.', $this->paginate);
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "id" => "required|exists:devices,id",
        ]);

        if ($validator->fails()) {
            return Helper::errorResponse(422, $validator->errors()->all());
        }
        try {
            $device = Device::find($input['id']);
            $device->delete();
            
            $data = array(
                "device" => $device
            );

            return Helper::successResponse($data, 'Successfully Record Updated.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

    public function deviceTokenUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "uuid" => "required",
            "token" => "required",
            "type" => "required",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(422, $validator->errors()->all());
        }

        try {

            $device = Device::updateOrCreate(
                    [
                        'user_id'=> $input['user_id'],
                        'type'=> $input['type'],
                        'uuid'=> $input['uuid'],
                    ],
                    [
                        'user_id'=> $input['user_id'],
                        'type'=> $input['type'],
                        'uuid'=> $input['uuid'],
                        'token'=> $input['token'],
                    ]
                );

            $data = array(
                "device" => $device
            );
            return Helper::successResponse($data, 'Successfully Record Updated.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }

}
