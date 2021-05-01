<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Validator;

class ContactController extends Controller
{

    public function ContactForm(Request $request)
    {
        
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required",
            "phone" => "required",
            "message" => "required",
        ]);
        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return Helper::errorResponse(44, $validator->errors()->all());
        }

        try {
            $from = config('mail.from.address');
            $to = config('mail.mail_to_address');
            // $to = $admin->email;
            $subject = "VPC Contact Form Request.";

            $body = '';
            $body .= "\n VPC Contact Form Request.";
            $body .= "\n\n First Name: ".$input['first_name'];
            $body .= "\n Last Name: ".$input['last_name'];
            $body .= "\n E-mail Address: ".$input['email'];
            $body .= "\n Phone Number: ".$input['phone'];
            $body .= "\n Message: ".$input['message'];

            $sendMail = Helper::sendMailSimple($body, $subject, $to, $from);

            return Helper::successResponse([], 'Successfully Send Contact Form Request.');
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getCode(), $e->getMessage());
        }
    }
}
