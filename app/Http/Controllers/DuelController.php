<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attempt;
use App\Challange;
use App\QuizDomain;
use App\AgeGroup;
use App\User;
use App\Contact;
use App\BlockUser;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\QuizType;
use App\Domain;
use App\FireBaseNotification;
use App\QuizRule;
use App\QuizTheme;
use App\CheckUserState;
class DuelController extends Controller
{
           
    public function create_duel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'difficulty_level_id' => 'required',
            'quiz_speed_id' => 'required',
            'domains' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        if (!age_group_by_user($request->user_id)) {
            return response()->json(['status' => 201, 'data' => '', 'message' => 'Age group not found..']);
        }
        $quiz_type = QuizType::where('name', 'like', '%Duel%')->where('no_of_player', 2)->latest()->first();

        if (empty($quiz_type)) {
            return response()->json(['status' => 204, 'message' => 'Dual type quiz not found', 'data' => array()]);
        }
        $oldquizcheck = Attempt::where('user_id', $request->user_id)->where('quiz_type_id',2)->where('end_at', null)->latest()->first();
    
        if ($oldquizcheck) {
            if (Carbon::now()->parse($oldquizcheck->created_at)->diffInSeconds() < 180) {

                return response()->json(['status' => 201, 'message' => 'Wait for 180 sec', 'data' => array()]);
            }
        }
        $oldquizs = Attempt::where('user_id', $request->user_id)->where('quiz_type_id', 2)->where('end_at', null)->get();
        if ($oldquizs) {
            foreach ($oldquizs as $oldquiz) {
                if (Carbon::now()->parse($oldquiz->created_at)->diffInSeconds() > 180) {
                    $oldquiz->deleted_at = date('Y-m-d h:i:s');
                    $oldquiz->save();
                }
            }
        }
        $data = new Attempt;
        $data->user_id = $request->user_id;
        $data->quiz_type_id = $quiz_type->id;
        $data->difficulty_level_id = $request->difficulty_level_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->save();

        // Create dual link
        $dual = Attempt::where('id', $data->id)->first();
        $dual->link = "cul.tre/duel#" . $data->id;
        $dual->save();

        $domain = new QuizDomain;
        $domain->attempts_id = $data->id;
        $domain->domain_id = $request->domains;
        $domain->save();

        $quiztheme = new QuizTheme;
        $quiztheme->quiz_id = $data->id;
        $quiztheme->user_id = $request->user_id;
        $quiztheme->theme_id = $request->theme_id;
        $quiztheme->save();

        $dual = [];
        $dual['dual_id'] = $data->id;
        $dual['user'] = ucwords(strtolower($data->user->name));
        $domains = explode(',', $request->domains);
        $dual['domain'] = Domain::select('id', 'name')->whereIn('id', $domains)->get()->toArray();
        $dual['quiz_speed'] = ucwords(strtolower($data->quiz_speed->name));
        $dual['difficulty'] = ucwords(strtolower($data->difficulty->name));
        $dual['quiz_type'] = ucwords(strtolower($data->quiz_type->name));
        //  $dual['link']=Attempt::where('id',$data->id)->first()->link;
        $dual['created_date'] = date('d-M-Y', strtotime($data->created_at));

        return response()->json(['status' => 200, 'message' => 'Dual Created', 'data' => $dual]);
    }

    public function get_all_users(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'dual_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        // Get all  user of friend list
        $friends =  Contact::where('friend_one', $req->user_id)->orWhere('friend_two', $req->user_id)->get();
        $user = [];
        foreach ($friends as $friend) {
            // check if user added by current user
            if ($friend->friend_one == $req->user_id) {
                $otheruserid = $friend->friend_two;
            }
            // check if current user added by other users
            if ($friend->friend_two == $req->user_id) {
                $otheruserid = $friend->friend_one;
            }
            // check if user blocked or not
            if (BlockUser::where('blocked_by', $req->user_id)->where('blocked_to', $otheruserid)->first()) {
                continue;
            }


            $user[] = $otheruserid;
        }
        $users = User::whereIn('id', $user)->where('type', '2')->get();
        $data = [];
        // $challange = Challange::where('attempt_id',$req->dual_id)->where('to_user_id',$user->id)->whereDate('created_at',carbon::now())->first();

        foreach ($users as $user) {
            if ($req->hide_busy) {
                if (checkUser($user->id)) {
                    continue;
                }
            }
            $age = Carbon::parse($user->dob)->age;
            $allUsers['id'] = $user->id;
            $allUsers['name'] = ucwords(strtolower($user->name));

            if ($ageGroup = AgeGroup::where('from', '<=', $age)->where('to', '>=', $age)->first()) {
                $allUsers['age_group'] = ucwords(strtolower($ageGroup->name));
            } else {
                $allUsers['age_group'] = "";
            }
            if ($user->country) {
                $allUsers['country'] = $user->country->country_name->name;

                $allUsers['flag_icon'] = url('/flags') . '/' . strtolower($user->country->country_name->sortname) . ".png";
            } else {
                $allUsers['flag_icon'] = url('/flags/') . strtolower('in') . ".png";
            }
            if (checkUser($user->id)) {
                $allUsers['status'] = "Busy";
            }
            else
            {
                $allUsers['status'] = "Online";
            }
            
            if (Challange::where('attempt_id', $req->dual_id)->where('to_user_id', $user->id)->whereDate('created_at', carbon::now())->first()) {
                $allUsers['request'] = "1";
            } else {
                $allUsers['request'] = "0";
            }
            if (isset($user->profile_image)) {
                $allUsers['image'] = url('/storage') . '/' . $user->profile_image;
            } else {
                $allUsers['image'] = '';
            }
            $data[] = $allUsers;
        }



        return response()->json(['status' => 200, 'message' => 'all users', 'data' => $data]);
    }
    public function send_invitation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'from_id' => 'required',
            'dual_id' => 'required',
            'to_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        // Check if user blocked or not 
        $blockuser = BlockUser::where('blocked_by', $req->to_id)->where('blocked_to',$req->from_id)->first();
        if($blockuser){
            return response()->json(['status' => 201, 'data' => [], 'message' => "You can not send request to this user"]);
 
        }
       
        // Check if already request accepted by other user
        $checkifaccept =   Challange::where('attempt_id', $req->dual_id)
             ->where('status', '1')
            ->where('from_user_id', $req->from_id)->first();
        if ($checkifaccept) {
            if($checkifaccept->to_user_id == $req->to_id){
              $message='You are already sent the request';  
            }else{
                $message = 'You cant not send the request at this point';   
            }
            return response()->json(['status' => 201, 'data' => [], 'message' => $message]);
        } 
        $challenge =   Challange::where('attempt_id', $req->dual_id)
            // ->where('status', '0')
            ->where('from_user_id', $req->from_id)->where('to_user_id',$req->to_id)->whereDate('created_at', carbon::now())->latest()->first();

        if (isset($challenge)) {
            if ($challenge->status == '0') {
                if (carbon::now()->parse($challenge->created_at)->diffInSeconds() < 60) {
                    return response()->json(['status' => 201, 'data' => [], 'message' => 'Sorry! Wait for 60 sec or till accept the request.']);
                }
            }
        }
        // if ($challenge) {
        //     return response()->json(['status' => 422, 'data' => [], 'message' => "Sorry You have already sent this user request for the dual quiz."]);
        // }
        // else
        // {
        $challange = Challange::where('attempt_id', $req->dual_id)->where('from_user_id', $req->from_id)
            ->where('to_user_id', $req->to_id)
            ->whereDate('created_at', carbon::now())->get()->count();
        if ($challange >= 3) {
            return response()->json(['status' => 422, 'data' => '', 'message' => "Sorry You can not send invitations to a single user more then 3 times for a particular quiz."]);
        } else {
        $challange = new Challange;
        $challange->to_user_id = $req->to_id;
        $challange->from_user_id = $req->from_id;
        $challange->attempt_id = $req->dual_id;
        $challange->status = '0';
        $challange->save();

        //notification

        $attempt = Attempt::where('id', $challange->attempt_id)->first();
        $data = [
            'title' => 'Invitation Received.',
            'token' => $challange->to_user->token,
            'link' => $attempt->link,
            'type' => 'dual',
            //   'from'=>$challange->from_user->name,
            'message' => 'You have a new request from' .' '. $challange->from_user->name,
        ];
        sendNotification($data);

        $savenoti = new FireBaseNotification;
        $savenoti->user_id = $challange->to_user->id;
        $savenoti->link = $attempt->link;
        $savenoti->type = 'dual';
        $savenoti->message = 'You have a new request from ' . ' ' . $challange->from_user->name;
        $savenoti->title = 'Dual Invitation send.';
        $savenoti->status = '0';
        $savenoti->save();

        return response()->json(['status' => 200, 'message' => 'Invitation Sent Successfully.']);
        }
        // }

    }
    public function accept_invitation(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'dual_link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        $attempt = Attempt::where('link', $req->dual_link)->first();
        if (empty($attempt)) {
            return response()->json(['status' => 204, 'message' => 'Sorry! Link has been expired. or not found']);
        }
        $challenge = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $req->user_id)->latest()->first();
        if (carbon::now()->parse($attempt->created_at)->diffInSeconds() > 180) {
            return response()->json(['status' => 200, 'message' => 'Sorry! Invitation has been expired.']);
        }
        if (empty($challenge)) {
            $challenge = new Challange;
            $challenge->to_user_id = $req->user_id;
            $challenge->from_user_id = $attempt->user_id;
            $challenge->attempt_id = $attempt->id;
            $challenge->status = '1';
            $challenge->save();
            // return response()->json(['status' => 204, 'message' => 'Invitation not send yet to user']);
        }

         

            if ($attempt->challange_id != "") {
                return response()->json(['status' => 422, 'data' => '', 'message' => 'Someone has already accepted the request. try next time!']);
            } else {
                // update attempts table
                $attempt->challange_id = $req->user_id;
                $attempt->save();

                $data = [
                    'title' => 'Dual Invitation accepted.',
                    'token' => $challenge->from_user->token,
                    'link' => $attempt->link,
                    'type' => 'dual',
                    'message' => User::where('id', $challenge->to_user_id)->first()->name . " has been accepted the request. you can start quiz now",
                ];
            sendNotification($data);
                // Create new data for user who accepts the request

                $acceptuser = new Attempt;
                $acceptuser->user_id = $req->user_id;
                $acceptuser->parent_id = $attempt->id;
                $acceptuser->difficulty_level_id = $attempt->difficulty_level_id;
                $acceptuser->quiz_type_id = $attempt->quiz_type_id;
                $acceptuser->quiz_speed_id = $attempt->quiz_speed_id;
                $acceptuser->save();

                // Update challange table status to accepted
                $challenge->status = '1';
                $challenge->save();

               
                // Save notification
                $savenoti = new FireBaseNotification;
                $savenoti->user_id = $challenge->from_user->id;
                $savenoti->link = $attempt->link;
                $savenoti->type = 'dual';
                $savenoti->message = User::where('id', $req->user_id)->first()->name . " has been accepted the request. you can start quiz now";
                $savenoti->title = 'Dual Invitation accepted.';
                $savenoti->status = '0';
                $savenoti->save();

            // $response['quiz_id'] = $acceptuser->id;
            
           
            $data = [
                ['user_id' => $req->user_id,],
                ['user_id' => $attempt->user_id,]              
            ];
            CheckUserState::insert($data); // Eloquent approach
            return response()->json(['status' => 200, 'data' => $acceptuser->id, 'message' => 'Invitation Successfully accepted.']);
            
        }
    }
    public function generate_link(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'dual_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        if ($attempt = Attempt::where('user_id', $req->user_id)->where('id', $req->dual_id)->first()) {

            $data = [];
            $data['link'] = $attempt->link;
            return response()->json(['status' => 200, 'message' => 'Generated Link', 'data' => $data]);
        } else {
            return response()->json(['status' => 200, 'message' => 'Sorry! No dual quiz found.']);
        }
    }


    public function dual($id)
    {
        $data = Attempt::find($id);

        if (isset($data)) {
            $domain =  QuizDomain::where('attempts_id', $data->id)->first()->domain_id;

            $domains = explode(',', $domain);

            $dual = [];
            $dual['dual_id'] = $data->id;
            $dual['domain'] = Domain::select('id', 'name')->whereIn('id', $domains)->get()->toArray();
            $dual['quiz_speed'] = ucwords(strtolower($data->quiz_speed->name));
            $dual['difficulty'] = ucwords(strtolower($data->difficulty->name));
            $dual['link'] = $data->link;
            $dual['created_date'] = date('d-M-Y', strtotime($data->created_at));

            return response()->json(['status' => 200, 'data' => $dual, 'message' => 'Dual data']);
        } else {
            return response()->json(['status' => 201, 'data' => '', 'message' => 'Quiz not find']);
        }
    }

    // public function submit_exam(Request $req)
    // {
    //    $validator = Validator::make($req->all(), [
    //        'user_id' => 'required',
    //        'dual_id'=>'required',
    //    ]);

    //    if ($validator->fails()) {
    //        return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
    //    }
    // }

    // public function fetch_dual_question($id)
    // {
    //     $data = Attempt::find($id);

    //     if(isset($data)){ 

    //          return response()->json(['status' => 200, 'data' =>$data,'message' => 'Dual data']);  

    //     }else{
    //         return response()->json(['status' => 201, 'data' => '', 'message' => 'Quiz not find']);

    //     }
    // }

    public function get_dual_result(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dual_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422,  'message' => $validator->errors()]);
        }
        // $data = Attempt::where('id',$request->dual_id)->where('user_id',$request->user_id)->first();
        $data = Attempt::where('id', $request->dual_id)->first();

        if (isset($data)) {
            if (isset($data->parent_id)) {
                $data2 =  Attempt::where('id', $data->parent_id)->first();
            } else {
                $data2 =  Attempt::where('parent_id', $request->dual_id)->first();
            }
            if ($data->user_id == $request->user_id) {
                $user_data = $data;
                $otheruser_data = $data2;
            } else {
                // if ($data2->user_id != $request->user_id) {
                //     return response()->json(['status' => 201, 'data' => [], 'message' => 'User not found']);
                // }
                $user_data = $data2;
                $otheruser_data = $data;
            }
            $user = [];

            $user['user_id'] = $user_data->user_id;
            $user['name'] = $user_data->user->name;
            $user['xp'] = $user_data->xp;
            $user['percentage'] = ($user_data->result) ? $user_data->result:0;
            if (isset($user_data->user->profile_image)) {
                $user['image']  = url('/storage') . '/' . $user_data->user->profile_image;
            } else {
                $user['image']  = '';
            }
            if($user_data->status =='completed'){
                $user['is_submit'] = '1';   
            }else{
                $user['is_submit'] = '0';  
            }

            $response = [];
            $response['user_id'] = $otheruser_data->user_id;
            $response['name'] = $otheruser_data->user->name;
            $response['xp'] = $otheruser_data->xp;
            $response['percentage'] = ($otheruser_data->result) ? $otheruser_data->result : 0;
            if (isset($otheruser_data->user->profile_image)) {
                $response['image']  = url('/storage') . '/' . $otheruser_data->user->profile_image;
            } else {
                $response['image']  = '';
            }
            if ($otheruser_data->status == 'completed') {
                $response['is_submit'] = '1';
            } else {
                $response['is_submit'] = '0';
            }
            //Check whic user percentage is > (Greater)
             if($user['percentage']> $response['percentage']){
                $res[] = $user;
                $res[] = $response; 
             }else{
            $res[] = $response;
            $res[] = $user;
             }

           $relaseuser = CheckUserState::where('user_id',$request->user_id);
           ($relaseuser) ? $relaseuser->delete() : '';

            return response()->json(['status' => 200, 'user_data' => $user, 'result' => $res, 'message' => 'Dual data']);
        } else {
            return response()->json(['status' => 201,  'message' => 'Quiz not find']);
        }
    }

    public function dual_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dual_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $data = Attempt::where('id', $request->dual_id)->where('quiz_type_id', '2')->first();
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }
        if (isset($data->challange_id)) {
            $challenge = Challange::where('attempt_id', $request->dual_id)->first();

            $dual['dual_id'] = $data->id;
            $dual['name'] = $challenge->to_user->name ;
            $dual['image'] = url('/storage') . '/' . $challenge->to_user->profile_image;
         
            return response()->json(['status' => 200, 'data' => $dual, 'message' => 'Request accepted']);
        } else {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Request not accepted yet']);
        }
    }

    public function quiz_rules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $data = Attempt::where('id', $request->id)->where('quiz_type_id', 2)->first();
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }

        if (isset($data)) {
            if (isset($data->parent_id)) {
                $data2 =  Attempt::where('id', $data->parent_id)->first();
            } else {
                $data2 =  Attempt::where('parent_id', $request->dual_id)->first();
            }
        }
        
        $quiz_rules = QuizRule::select('more')->where('quiz_type_id', 2)->where('quiz_speed_id', $data2->quiz_speed_id)->where('status','1')->first();

        if (empty($quiz_rules)) {
            return response()->json(['status' => 204, 'message' => 'No rules found for the quiz', 'data' => []]);
        } else {
            // $data = json_decode($quiz_rules->more);
             $quiz_rules->more = json_decode($quiz_rules->more);
            $data = $quiz_rules->more;
            // $data = array_filter(array_values($data));
            return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
        }
    }

    public function reject_invitation(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'dual_link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        $attempt = Attempt::where('link', $req->dual_link)->first();
       
        if (empty($attempt)) {
            return response()->json(['status' => 204, 'data' => [], 'message' => 'Sorry! Link has been expired. or not found']);
        }
        $challenge = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $req->user_id)->latest()->first();

        if (empty($challenge)) {
            $data = [
                'title' => 'Duel Invitation rejected.',
                'token' =>User::where('id', $attempt->user_id)->first()->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'message' => User::where('id', $req->user_id)->first()->name . " had  rejected the request",
            ];
            sendNotification($data);
            return response()->json(['status' => 200, 'data' => [], 'message' => 'No invitation']);
        } else {
            // $challenge->deleted_at = date('Y-m-d h:i:s');
            $challenge->status='2';
            $challenge->save();


            $data = [
                'title' => 'Duel Invitation rejected.',
                'token' => $challenge->from_user->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'message' => User::where('id', $req->user_id)->first()->name . " had  rejected the request",
            ];
            sendNotification($data);

            return response()->json(['status' => 200, 'data' => [], 'message' => 'Rejected succesfully']);
        }
    }

    public function dualdetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'dual_link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        $data = Attempt::where('link', $request->dual_link)->where('started_at',null)->first();
        if (empty($data)) {
            return response()->json(['status' => 204, 'message' => 'Sorry! Link had expired. or not found']);
        }

        $domain =  QuizDomain::where('attempts_id', $data->id)->first()->domain_id;

        $domains = explode(',', $domain);

        $dual = [];
        if($data->quiz_type_id==3){
        $dual['quiz_room_id'] = $data->id;
        }else{
        $dual['dual_id'] = $data->id;
        }
        $dual['domain'] = implode(',', Domain::whereIn('id', $domains)->pluck('name')->toArray());
        $dual['quiz_speed'] = ucwords(strtolower($data->quiz_speed->name));
        $dual['difficulty'] = ucwords(strtolower($data->difficulty->name));
        $dual['link'] = $data->link;
        if (isset($data->user->profile_image)) {
            $dual['image']  = url('/storage') . '/' . $data->user->profile_image;
        } else {
            $dual['image']  = '';
        }
        if (isset($data->user)) {
            $dual['name']  = $data->user->name;
        } else {
            $dual['name']  = '';
        }
        // $dual['name'] = '';
        // $dual['image'] = '';
        $dual['created_date'] = date('d-M-Y', strtotime($data->created_at));

        return response()->json(['status' => 200, 'data' => $dual, 'message' => 'Dual data']);
    }


    public function duelrank(Request $request){

        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'dual_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $data = Attempt::where('id', $request->dual_id)->where('quiz_type_id', '2')->first();
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }

        $totalusers = Attempt::where('id', $request->dual_id)->orWhere('parent_id', $request->dual_id)->orderBy('marks', 'ASC')->get();
        if($totalusers->count() >= 2) {
            
        }

    }

    
}
