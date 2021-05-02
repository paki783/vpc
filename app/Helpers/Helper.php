<?php

namespace App\Helpers;

use App\Notification;
use Illuminate\Support\Facades\Mail;
use Storage;
use Image;
use Illuminate\Support\Facades\File;

class Helper
{
    private static $result = '';
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public static function successResponse($result = [], $message, $paginate = false, $code=200)
    {
        self::$result = $result;
        
        if ($paginate == true) {
            self::paginate($result);
        }

        $response = [
            'success' => true,
            'status_code'    =>$code,
            'message' => [$message],
           // 'message' =>$message,
            'data'   => self::$result
        ];
        return response()->json($response, 200);
    }

    public static function paginate($data = [])
    {
        $paginationArray = null;
        if ($data != null) {
            $paginationArray = array('list'=>$data->items(),'pagination'=>[]);
            $paginationArray['pagination']['total'] = $data->total();
            $paginationArray['pagination']['current'] = $data->currentPage();
            $paginationArray['pagination']['first'] = 1;
            $paginationArray['pagination']['last'] = $data->lastPage();
            if ($data->hasMorePages()) {
                if ($data->currentPage() == 1) {
                    $paginationArray['pagination']['previous'] = 0;
                } else {
                    $paginationArray['pagination']['previous'] = $data->currentPage()-1;
                }
                $paginationArray['pagination']['next'] = $data->currentPage()+1;
            } else {
                $paginationArray['pagination']['previous'] = $data->currentPage()-1;
                $paginationArray['pagination']['next'] =  $data->lastPage();
            }
            if ($data->lastPage() > 1) {
                $paginationArray['pagination']['pages'] = range(1, $data->lastPage());
            } else {
                $paginationArray['pagination']['pages'] = [1];
            }
            $paginationArray['pagination']['from'] = $data->firstItem();
            $paginationArray['pagination']['to'] = $data->lastItem();
            //$paginationArray;
            //
            return self::$result = $paginationArray;

            /// }else {
            //     $paginationArray = $data;
            //     return self::$result = $paginationArray;
            // }
        }
        //return $paginationArray;
    }
    
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function errorResponse($code = 401, $error = '', $errorMessages = [])
    {
        $response = [
            'success' => false,
            'code'    => $code,
            'message' => $error,
            'data'    => $errorMessages
        ];

        return response()->json($response, $code);
    }

    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz@#!ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateRandomNumber($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateRandomNumberString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function sendMailSimple($data, $subject, $to, $from, $cc = [], $bcc = [])
    {
        return $mail = Mail::raw($data, function ($message) use ($subject, $to, $from, $cc, $bcc) {
            $message->from($from, env('MAIL_FROM_NAME', 'VPC'));
            $message->replyTo($from, env('MAIL_FROM_NAME', 'VPC'));
            $message->subject($subject);

            if (isset($cc) && @$cc[0] != '') {
                foreach ($cc as $k => $v) {
                    $message->cc($v, null);
                }
            }
            if (isset($bcc) && @$bcc[0] != '') {
                foreach ($bcc as $k => $v) {
                    $message->bcc($v, null);
                }
            }
            
            $message->to($to, env('MAIL_FROM_NAME', 'VPC'));
        });
    }

    public static function getStorageUrl($type = 'storage', $file = "")
    {
        $path = '';
        switch ($type) {
            case 'storage':
                $path = '/storage/app/';
        }
        return config('app.url') . $path . $file;
    }

    public static function getCDNUrl($type = 'storage', $file = "")
    {
        $path = '';
        switch ($type) {
            case 'storage':
                $path = '/storage/app/';
        }
        return config('app.cdn_url') . $path . $file;
    }

    public static function getAppUrl($type = 'storage', $file = "")
    {
        $path = '';
        switch ($type) {
            case 'storage':
                $path = '/storage/app/';
        }
        return config('app.url') . $path . $file;
    }

    public static function getFileUrl($url)
    {
        $currentFileSystem = config('filesystems.default');
        if ($currentFileSystem == 'local' || $currentFileSystem == 'public') {
            return \URL::to('files/' . $url);
        } elseif ($currentFileSystem == 'rackspace') {
            if (config('app.is_secure')) {
                $storageUrl = config('filesystems.disks.rackspace.secure_url') . $url;
            } else {
                $storageUrl = config('filesystems.disks.rackspace.public_url') . $url;
            }
            return $storageUrl;
        } else {
            $storageUrl = \Storage::url($url);
            $storageUrl = str_replace('%40', '@', $storageUrl);
            return $storageUrl;
        }
    }

    public static function saveImage($file, $module = 'user', $dynamicFolder = '', $extensions = ['jpeg', 'png', 'gif', 'bmp', 'svg'])
    {
        $currentFileSystem = config('filesystems.default');
        if ($currentFileSystem == 'local' || $currentFileSystem == 'public') {
            if (config('app.check_file_size')) {
                $fileSize = $file->getClientSize() / 1000;
                $sysytemFileSize = (int) config('app.file_size_in_kb');
                if ($fileSize > $sysytemFileSize) {
                    return false;
                }
            }
            list($name, $ext) = explode('.', $file->hashName());
            if (in_array($file->guessExtension(), $extensions)) {
                $image = Image::make($file);

                $width = $image->width();
                $height = $image->height();

                if (config('app.image_sizes')) {
                    $store = Storage::put(config('app.public.files.' . $module . '.folder_name') . '/' . $dynamicFolder . '/' . $name . '.' . $ext, $image->resize($width, $height)->stream($ext, config('app.image_compression')));
                    $store = Storage::put(config('app.public.files.' . $module . '.folder_name') . '/' . $dynamicFolder . '/' . $name . '-2x.' . $ext, $image->resize($width / 2, $height / 2)->stream($ext, config('app.image_compression')));
                    $store = Storage::put(config('app.public.files.' . $module . '.folder_name') . '/' . $dynamicFolder . '/' . $name . '-3x.' . $ext, $image->resize($width / 3, $height / 3)->stream($ext, config('app.image_compression')));
                } else {
                    $store = Storage::put(config('app.public.files.' . $module . '.folder_name') . '/' . $dynamicFolder . '/' . $name . '.' . $ext, $image->resize($width, $height)->stream($ext, config('app.image_compression')));
                }
                return $name . '.' . $ext;
            } else {
                return false;
            }
        } else {
            $folder = $dynamicFolder == '' ? 'misc' : $dynamicFolder;

            try {
                //$resp = Storage::put($file->getClientOriginalExtension(), $file);
                $resp = Storage::putFile($folder, $file, 'public');
                $path = $resp;
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            return $path;
        }
    }

    public static function deleteFile($path)
    {
        $currentFileSystem = config('filesystems.default');
        if ($currentFileSystem == 'local' || $currentFileSystem == 'public') {
            if ($path !== "") {
                $delete = File::delete($path);
                @unlink($path);
                return $delete;
            } else {
                return false;
            }
        } elseif ($currentFileSystem == 's3') {
            Storage::disk('s3')->delete($path);
        }
    }

    public static function imageStoragePath($type = '')
    {
        if ($type == "url") {
            return config('app.url') . "/storage/app/";
        } else {
            return public_path() . "/../storage/app/";
        }
    }

    public static function getFinalSql($query)
    {
        $sql_str = $query->toSql();
        $bindings = $query->getBindings();

        $wrapped_str = str_replace('?', "'?'", $sql_str);

        // return str_replace_array('?', $bindings, $wrapped_str);

        echo str_replace_array('?', $bindings, $wrapped_str);
        die;
    }
    public static function regions()
    {
        return [
            "Global",
            "Asia",
            "Africa",
            "Australia",
            "Antarctica",
            "Central America",
            "Europe",
            "North America",
            "South America",
        ];
    }


    public static function print_b($value)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
    public static function setIndexes($array)
    {
        $res = [];
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                $res[] = 	$v;
            }
            return $res;
        }
        return false;
    }

    public static function subsets($S, $L)
    {
        $a = $b = 0;
        $subset = [];
        $result = [];
        while ($a < count($S)) {
            $current = $S[$a++];
            $subset[] = $current;
            if (count($subset) == $L) {
                $result[] = $subset;
                array_pop($subset);
            }
            if ($a == count($S)) {
                $a = ++$b;
                $subset = [];
            }
        }
        return $result;
    }

    public static function makeTeams($arr, $match = 2)
    {
        $count = count($arr);
        

        if ($count > 0) {
            if (floor((@$count/@$match)) == 1 || $match == 0) {
                return false;
            } else {
                $newArray = [];
                $myArray = $arr;
                if ($match > 1) {
                    $loopC = 0;
                    for ($i=0; $i < ($count/$match); $i++) {
                        // echo $i;
                        // die;
                        for ($j=0; $j < $match; $j++) {
                            if ($loopC == 0) {
                                $newArray[$i][] = $arr[0];
                                unset($arr[0]);
                                $loopC = 1;
                            } else {
                                $newArray[$i][] = $arr[(count($arr)-1)];
                                unset($arr[(count($arr)-1)]);
                                $loopC = 0;
                            }
                            $arr = self::setIndexes($arr);
                        }
                        // print_b($newArray[$i]);
                    }
                
                    return $newArray;
                } else {
                    return $arr;
                }
            }
        }
        return false;
    }

    public static function roundName($round)
    {
        if ($round <= 3) {
            $roundNameData =[
                '3' => 'Quarter Final',
                '2' => 'Semi Final',
                '1' => 'Final'
            ];
            $res = '';
            foreach ($roundNameData as $k => $v) {
                if ($k == $round) {
                    $res = $v;
                    break;
                }
            }
            return $res;
        } else {
            return "Round Of ".$round;
        }
    }

    public static function roundStage($round)
    {
        return "1/".$round;
    }
    
    public static function dividedByCount($number, $base)
    {
        return floor(log($number, $base)); // <== SOLUTION
    }

    public static function pushNotification($firebase_token, $title, $body, $image_url = '', $action = '',$actionDestination = '',$send_to = "single",$topic = null,$extra = null, $keyfor = "user") {
        $firebase_api = "AAAAPDt1SH4:APA91bFUxQ1sGOVeKua4HnMGE1CTmscmSN_1_qRQgci80IjhKyuHFbIGWqCdwfza-cl00-fAzovSuYwrqrPaV2TNgOenqZ49g5mfzKEoGh5K-GCYmnI7fxxfBMGFZK9BxEKtj7rwaicC";
        
        $title = (isset($title) && @$title != null)?$title:'';
        $body = (isset($body) && @$body != null)?$body:'';
        $imageUrl = (isset($image_url) && @$image_url != null)?$image_url:'';
        $action = (isset($action) && @$action != null)?$action:'';

        $actionDestination = (isset($actionDestination) && @$actionDestination != null)?$action:'';

        if($actionDestination ==''){
            $action = '';
        }

        $notification = array(
            'title' => $title,
            'body' => $body,
            'image' => $image_url,
            'action' => $action,
            'action_destination' => $actionDestination,
        );

        $mydata = array();
        $mydata['notification'] = $notification;
        $mydata['extra'] = $extra;
        // echo "<pre>";
        // print_r($mydata);
        // die;
        if($send_to =='topic'){
            $fields = array(
                'to' => '/topics/' . $topic,
                'data' => $mydata['notification'],
                'notification' => $mydata['notification'],
            );
        }else{
            $fields = array(
                'to' => $firebase_token,
                'data' => $mydata['extra'],
                'notification' => $mydata['notification'],
            );
        }
        // die;
        // Set POST variables

        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . $firebase_api,
            'Content-Type: application/json'
        );
        // print_b($headers);
        // Open connection

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, JSON_FORCE_OBJECT));
        // Execute post
        $result = curl_exec($ch);
        if($result === FALSE){

            die('Curl failed: ' . curl_error($ch));

        }
        // Close connection
        curl_close($ch);
        return $result;
        //return array();
    }

    public static function sendNotification($user_id, $notificationExtra){

        // $token = User::where('id', $user_id)->first();
        if(!empty($token)){   
            $extra = [
                "type" => !empty($notificationExtra['type']) ? $notificationExtra['type'] : "",
            ];
            
            self::pushNotification($token->remember_token, $notificationExtra['title'], $notificationExtra['body'], "", "", "", "single", null, $extra);
            Notification::create([
                'user_id' => $user_id,
                'is_read' => 0,
                "title" => $notificationExtra['title'],
                "body" => $notificationExtra['body'],
            ]);
        }
    }
}
