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
            $data = [];
            $response = [];
            foreach ($contacts as $contact) {
                $user = User::where('id', $contact->friend_two)->first();
                $data['id'] = $contact->id;
                $data['name'] = $user->name;

                if (isset($user->profile_image)) {
                    $data['image'] = url('/storage') . '/' . $user->profile_image;
                } else {
                    $data['image'] = '';
                }
                if(isset($user->refrence_code)){
                    $data['link'] = "cul.tre/invite#".$user->refrence_code;

                }else{
                    $data['link'] = "";
 
                }
                $response['contact'][] = $data;
            }

            foreach ($duals as $dual) {
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
                $data['domain'] = implode(',',Domain::whereIn('id', $domains)->pluck('name')->toArray());
                $data['quiz_speed'] = ucwords(strtolower($dualdata->quiz_speed->name));
                $data['difficulty'] = ucwords(strtolower($dualdata->difficulty->name));
                $response['dual'][] = $data;
            }
            return response()->json(['status' => 200, 'data' => $response, 'message' => 'Data']);
        } else {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'User not found..']);
        }
    }
}
