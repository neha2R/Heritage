<?php
namespace App\Traits;

use App\User;
use App\Notification;
use App\NotificationDetail;
use App\NotificationSetting;
trait NotificationToUser {

    public function NewTournament() {
      $noti =  NotificationDetail::where('title','like',
            '%New Tournaments%')->first();
       $users =  NotificationSetting::where('notification_details_id',$noti->id)->get();
        foreach($users as $user){
            $userdata = User::find($user->user_id);

            $data['title']='New tournament';
            $data['type'] = 'tournament';
            $data['message'] = 'New tournament has been created';
            $data['token'] = $userdata->token;
            $this->sendNotification($data);   
        }
    }

    public function NewPost()
    {
        $noti =  NotificationDetail::where(
            'title',
            'like',
            '%New Posts%'
        )->first();
        $users =  NotificationSetting::where('notification_details_id', $noti->id)->get();
        foreach ($users as $user) {
            $userdata = User::find($user->user_id);
            $data['title'] = 'New Post';
            $data['type'] = 'post';
            $data['message'] = 'New post has been created';
            $data['token'] = $userdata->token;
            $this->sendNotification($data);
        }
    }

    public function NewProduct()
    {
        $noti =  NotificationDetail::where(
            'title',
            'like',
            '%New Products%'
        )->first();
        $users =  NotificationSetting::where('notification_details_id', $noti->id)->get();
        foreach ($users as $user) {
            $userdata = User::find($user->user_id);

            $data['title'] = 'New Products';
            $data['type'] = 'product';
            $data['message'] = 'New products has been created';
            $data['token'] = $userdata->token;
            $this->sendNotification($data);
        }
    }

    public function Newexp()
    {
        $noti =  NotificationDetail::where(
            'title',
            'like',
            '%New Experience%'
        )->first();
        $users =  NotificationSetting::where('notification_details_id', $noti->id)->get();
        foreach ($users as $user) {
            $userdata = User::find($user->user_id);

            $data['title'] = 'New Experience';
            $data['type'] = 'experience';
            $data['message'] = 'New Experience found ! Please hurry up';
            $data['token'] = $userdata->token;
            $this->sendNotification($data);
        }
    }
    
    function sendNotification($data)
    { 
        // dd($data); 
        $msg = array(
            'title' => $data['title'],
            'body' => $data['message'],
            'vibrate' => 1,
            'sound' => 1,
        );
        if(isset($data['room_id'])){
            $msg['room_id'] = $data['room_id'];
        }
        if (isset($data['type'])) {
            $msg['type'] = $data['type'];
        }
        $mytoken = User::where('token', $data['token'])->first();
        if ($mytoken->device_id == '1') {
            $fields = array(
                'registration_ids' => array($data['token']),
                'data' => $msg,
                'notification' => $msg,

            );
        }else{
        //this is for android
        $fields = array(
            'registration_ids' => array($data['token']),
            'data' => $msg,
            'priority' => 'high',
        );
    }
        //this is for ios
        //     $fields = array
        //     (
        //     'registration_ids' => $registrationIds,
        //     'notification' => $msg,
        //  );

        $headers = array(
            'Authorization: key=' . 'AAAA6AYxYl0:APA91bH_s_VK0dzVunHIttmAsUaRWUIuzas6iF4LzAep06wRC72Ut-jf4OaITrk3sJIb0BR4nast_hMZlUSdDZFnW_InOdiyI0R4N1QbquNVlKfZ1lmV6mYDyy-KsO2P12ZmajAgQCho',
            'Content-Type: application/json',
        );
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
         
        curl_close($ch);
        return true;
    }

    function startroom($users,$quizid){
        foreach ($users as $user) {
            // $userdata = User::find($user);
            $data['title'] = 'Quiz room started';
            $data['room_id'] = $quizid;
            $data['message'] = 'Started';
            $data['type'] = 'quizroomstart';
            $data['token'] = $user->token;
            $this->sendNotification($data);
        }
    }

    function disbandroom($users)
    {
        foreach ($users as $user) {
            // $userdata = User::find($user);
            $data['title'] = 'Quiz room deleted';
            $data['message'] = 'Disband';
            $data['type'] = 'quizroom';
            $data['token'] = $user->token;
            $this->sendNotification($data);
        }
    }
}
