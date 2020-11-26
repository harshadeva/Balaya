<?php

namespace App\Http\Controllers\Api;

use App\NotificationId;
use App\NotificationToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiNotificationController extends Controller
{
    public function store(Request $request){
        $validator = \Validator::make($request->all(), [
            'token' => 'required',
        ], [
            'token.required' => 'Token should be provided!',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $notificationID = NotificationToken::where('idUser',Auth::user()->idUser)->where('status',1)->first();
        if($notificationID == null){
            $notificationID = new NotificationToken();
            $notificationID->status = 1;
            $notificationID->idUser = Auth::user()->idUser;
        }
        $notificationID->token = $request['token'];
        $notificationID->save();
        return response()->json(['success' =>'Token Saved' ,'statusCode'=>0]);
    }

    function callNotificationTemp(Request $request){
        $userId = $request['user'];
        $title = $request['title'];
        $message = $request['message'];
        $result =   $this->sendNotification($userId,$title,$message,'','');
        return $result;
    }

    function sendNotification($userId,$title,$message,$icon,$action){

        $user = NotificationToken::where('idUser',$userId)->where('status',1)->first();
        if($user == null)
        {
            return 'User token not found';
        }
        $token = $user->token;

        $url ="https://fcm.googleapis.com/fcm/send";

        $fields=array(
            "to"=>$token,
            "notification"=>[
                "body"=>$message,
                "title"=>$title,
                "icon"=>$icon,
                "click_action"=>$action
            ]
        );

        $headers=[
            'Authorization: key=AAAALe0Xrdo:APA91bFsZVpgstTMKQMQ5Y-ryXddbriblVI1uOoCGxVW0_Pm2RpnpFSeSNctSlGmTQccREUiQyDOsP1usVrBAlY7TcOXjW1SAt4wK3B-D4uO_Rei_oUArUohpTQGFV-DZdR97S_hMD5E',
            'Content-Type:application/json'
        ];

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result=curl_exec($ch);
//        print_r($result);
        curl_close($ch);
        return $result;
    }
}
