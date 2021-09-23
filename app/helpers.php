<?php

function sendNotification($data)
{
        $msg = array
        (
            'title1' => $data['title'],
            'body' => $data['message'],
            'link' => $data['link'],
            'vibrate' => 1,
            'sound' => 1,
        );
        //this is for android
        $fields = array
            (
            'registration_ids' => array($data['token']),
            'data' => $msg,
            'priority' => 'high',
        );

        //this is for ios
        //     $fields = array
        //     (
        //     'registration_ids' => $registrationIds,
        //     'notification' => $msg,
        //  );

        $headers = array
            (
            'Authorization: key=' .'AAAAZktK8xw:APA91bFMvIunDvN6clsReOUXpB61q8JcGb8FCBbM4sqXooprngVLZgrBy4YCiftkLTaB4qORqU72ONC29_gjaV1lB-oYHShbM90BKnLwGKkmO9Vll6vamNEqKZTxa7RHUCWWu8zsjmyb',
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