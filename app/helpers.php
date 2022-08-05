<?php
use App\User;
use App\AgeGroup;
use Carbon\Carbon;
use App\CheckUserState;
use App\Privacy;
use App\PrivacyDetail;
use App\PrivacySetting;
function sendNotification($data)
{
    $msg = array(
        'title' => $data['title'],
        'body' => $data['message'],
        'link' => $data['link'],
        'type' => $data['type'],
         'vibrate' => 1,
        'sound' => 1,
    );
    if ($data['is_ios'] == '1') {
        $fields = array(
            'registration_ids' => array($data['token']),
            'data' => $msg,
            'notification' => $msg,

        );
    } else {
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
    //   dd($result);
    curl_close($ch);
    return true;
}


function notify($data)
{
    $msg = array(
        'title' => $data['title'],
        'body' => $data['message'],
        'type' => '',
        'vibrate' => 1,
        'sound' => 1,
    );
    if ($data['is_ios'] == '1') {
        $fields = array(
            'registration_ids' => array($data['token']),
            'data' => $msg,
            'notification' => $msg,

        );
    } else {
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
    // dd($result);
    curl_close($ch);
    return true;
}

function age_group_by_user($user_id)
{
    $user = User::find($user_id);
    $age = Carbon::parse($user->dob)->age;

    $ageGroup = AgeGroup::where('from', '<=', $age)->where('to', '>=', $age)->where('status','1')->first();
    return $ageGroup;
}


function checkUser($id)
{
    return CheckUserState::where('user_id', $id)->first();
}

function userProfileSetting($userid){
    $privacy = Privacy::find(1);
    $detailids = PrivacyDetail::where('privacy_id', $privacy->id)->pluck('id')->toArray();
    $settings= PrivacySetting::where('user_id',$userid)->whereIn('privacy_details_id', $detailids)->latest()->first();
    if (!isset($settings)) {
        return 'all';      // default setting   
    }
  
    // foreach($settings as $setting){
        $detail =   PrivacyDetail::find($settings->privacy_details_id);
  
        if($detail->title=='Anyone') {
          return 'all';
        }
        if ($detail->title == 'Only me') {
            return 'me';
        }

        if ($detail->title == 'Only user i have added') {
            return 'me';
        }

        return null;

    // }
}

