<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Challange;
use App\Contact;
use App\User;
use App\Attempt;
use App\QuizDomain;
use App\Domain;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Dashboard API response
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $user =  User::find($request->user_id);

        if (isset($user)) {
            $contacts = Contact::where('friend_two', $request->user_id)->where('status', '0')->get();
            $duals = Challange::where('to_user_id', $request->user_id)->where('status', '0')->get();
            $acceptinvitations = Challange::where('from_user_id', $request->user_id)->where('status', '1')->get();

            $data = [];
            $acceptdata = [];
            $response = [];
            foreach ($contacts as $contact) {
                $user = User::where('id', $contact->friend_one)->first();
                $data['id'] = $contact->id;
                $data['name'] = $user->name;

                if (isset($user->profile_image)) {
                    $data['image'] = url('/storage') . '/' . $user->profile_image;
                } else {
                    $data['image'] = '';
                }
                if (isset($user->refrence_code)) {
                    $data['link'] = "cul.tre/invite#" . $user->refrence_code;
                } else {
                    $data['link'] = "";
                }
                $response['contact'][] = $data;
            }
            $mydata = [];
            foreach ($duals as $dual) {
                if (Attempt::find($dual->attempt_id)) {
                    $check = Attempt::find($dual->attempt_id);
                    if (Carbon::now()->parse($check->created_at)->diffInSeconds() < 180) {  // Duel is not older than 3 minute

                        $user = User::where('id', $dual->from_user_id)->first();

                        $data['name'] = $user->name;
                        $data['id'] = $dual->id;
                        if (isset($user->profile_image)) {
                            $data['image'] = url('/storage') . '/' . $user->profile_image;
                        } else {
                            $data['image'] = '';
                        }
                        $data['link'] = Attempt::where('id', $dual->attempt_id)->first()->link;
                        $data['dual_id'] = $dual->attempt_id;
                        $domain =  QuizDomain::where('attempts_id', $dual->attempt_id)->first()->domain_id;

                        $dualdata = Attempt::find($dual->attempt_id);
                        $domains = explode(',', $domain);
                        $data['domain'] = implode(',', Domain::whereIn('id', $domains)->pluck('name')->toArray());
                        $data['quiz_speed'] = ucwords(strtolower($dualdata->quiz_speed->name));
                        $data['difficulty'] = ucwords(strtolower($dualdata->difficulty->name));
                        $mydata[] = $data;
                    }
                }
            }
            foreach ($acceptinvitations as $acceptinvitation) {
                $challange = Attempt::find($acceptinvitation->attempt_id);

                if (Attempt::find($acceptinvitation->attempt_id)) {
                    if (Carbon::now()->parse($challange->created_at)->diffInSeconds() < 180) {  // Duel is not older than 3 minute

                        $accept['id'] = $acceptinvitation->attempt_id;
                        $acceptdata[] = $accept;
                    }
                }
            }
            $response['accept'] = $acceptdata;
            $response['dual'] = $mydata;
            return response()->json(['status' => 200, 'data' => $response, 'message' => 'Data']);
        } else {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'User not found..']);
        }
    }

    public function download($id)
    {
        return view('download');
    }

    public function link_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => [], 'message' => $validator->errors()]);
        }
          
        $string = explode('/',$request->link);
        $string = explode('#',$string['1']);
        $response = [];
        
        if($string['0']=='duel'){
            $data = Attempt::where('link', $request->link)->first();
            if (empty($data)) {
                return response()->json(['status' => 204, 'message' => 'Sorry! Link has been expired. or not found']);
            }
            $domain =  QuizDomain::where('attempts_id', $data->id)->first()->domain_id;

            $domains = explode(',', $domain);

            
            $response['dual_id'] = $data->id;
            $response['domain'] = implode(',', Domain::whereIn('id', $domains)->pluck('name')->toArray());
            $response['quiz_speed'] = ucwords(strtolower($data->quiz_speed->name));
            $response['difficulty'] = ucwords(strtolower($data->difficulty->name));
            $response['link'] = $data->link;
            $response['created_date'] = date('d-M-Y', strtotime($data->created_at));
            // $response['type'] = 'dual';
            return response()->json(['status' => 200, 'data' => $response, 'type' => 'duel', 'message' => 'Dual data']);

        }
        else if ($string['0'] == 'invite') {
            
            $user = User::where('refrence_code', $string[1])->first();
           
            if (!$user) {
                return response()->json(['status' => 201, 'data' => [], 'message' => 'Link is not valid']);
            }
            $oldFriend = Contact::where('friend_one', $user->id)->where('friend_two', $request->user_id)->first();
            if (isset($oldFriend) && $oldFriend->status == '1' ) 
            {
                return response()->json(['status' => 201, 'data' => [], 'message' => 'Friend already added']);
            }
            $data['id'] = $user->id;
            $data['name'] = $user->name;

            if (isset($user->profile_image)) {
                $data['image'] = url('/storage') . '/' . $user->profile_image;
            } else {
                $data['image'] = '';
            }
           
            $data['link'] = $request->link;
            
            $response = $data;
            return response()->json(['status' => 200, 'data' => $response,'type'=> 'invite', 'message' => 'Contact data']);

        }

        else{
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Not a valid link']);
 
        }
        


    }
}
