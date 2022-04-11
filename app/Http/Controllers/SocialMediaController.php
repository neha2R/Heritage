<?php
// header('Content-Type: application/json');

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;

class SocialMediaController extends Controller
{
    //

    public function fbdeleterequest(Request $request){
        // $signed_request = $_POST['signed_request'];
        // $data = $this->parse_signed_request($signed_request);
        // $user_id = $data['user_id'];

        // Start data deletion
        $code = uniqid();
        $status_url = URL::to('/').'/'."fbdeletioncheck?id=$code" ; // URL to track the deletion
        $confirmation_code = $code; // unique code for the deletion request

        $data = array(
            'url' => $status_url,
            'confirmation_code' => $confirmation_code
        );
        echo json_encode($data);
    }

    function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = "appsecret"; // Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function test(){
        return view('test');
    }

    public function fbdeletioncheck(Request $request){
        $data = array(
            'message' => 'Your delete request is still pending please wait for 3 months',
            
        );
        echo json_encode($data);
    }

    public function privacypolicy(){
        return view('privacypolicy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function twittercallback(Request $request){
       return view('twittercallback');
    }
}
