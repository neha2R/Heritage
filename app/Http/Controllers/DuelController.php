<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
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
use App\QuizSpeed;
use App\Duel_entry_room;

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
        $oldquizcheck = Attempt::where('user_id', $request->user_id)->where('quiz_type_id', 2)->where('end_at', null)->latest()->first();

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
     public function create_duelrandom(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => array(), 'message' => $validator->errors()]);
        }

       
        $isuser = Duel_entry_room::where('user_id', $request->user_id)->first();
         if(!$isuser)
         {
          
            $data = new Duel_entry_room;
            $data->user_id = $request->user_id;
         
            $data->save();
         }
       
        
        $dualrandomquiz = Duel_entry_room::where('user_id', "!=", $request->user_id)->pluck('user_id')->toArray();

        // Create dual link
       
                $users = User::whereIn('id', $dualrandomquiz)->where('type', '2')->get();

                $data = [];


        // All user who accept invitation
        foreach ($users as $user) {
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
            $allUsers['status'] = "Online";
            if (isset($user->profile_image)) {
                $allUsers['image'] = url('/storage') . '/' . $user->profile_image;
            } else {
                $allUsers['image'] = '';
            }
            $data[] = $allUsers;
        }
        
          
        //  $dual['link']=Attempt::where('id',$data->id)->first()->link;
     //   $duel['created_date'] = date('d-M-Y', strtotime($data->created_at));
if($dualrandomquiz )
{
        return response()->json(['status' => 200, 'message' => 'You entered in duel room & Duel Random List Available.', 'data' => $data]);
}
else
{
    return response()->json(['status' => 202, 'message' => 'You entered in duel room but No other user found in Duel Random List.', 'data' => array()]);
 
}       
    }

    public function notify_sender(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => array(), 'message' => $validator->errors()]);
        }

        
        $isuser = Challange::where('to_user_id', $request->user_id)->where('is_duelrandom',  '1')->orderBy('id', 'DESC')->latest()->first();
        


if($isuser)
{
         $dualrandomquiz = Attempt::where('parent_id',  $isuser->attempt_id)->first();
         $allUsers['sender_id']=$isuser->from_user_id;
         $allUsers['duel_id']=$dualrandomquiz->id;
 $users = User::where('id', $isuser->from_user_id)->where('type', '2')->get();

             


        // All user who accept invitation
        foreach ($users as $user) {
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
            $allUsers['status'] = "Online";
            if (isset($user->profile_image)) {
                $allUsers['image'] = url('/storage') . '/' . $user->profile_image;
            } else {
                $allUsers['image'] = '';
            }
            
        }
        $oldquizs2 = Challange::where('from_user_id', $isuser->from_user_id)->where('to_user_id', $request->user_id)->where('is_duelrandom', '1')->first();
           if($oldquizs2)
                     {
                  $oldquizs2->deleted_at = date('Y-m-d h:i:s');
                     $oldquizs2->save();
                     }   
        //  $dual['link']=Attempt::where('id',$data->id)->first()->link;
     //   $duel['created_date'] = date('d-M-Y', strtotime($data->created_at));

        return response()->json(['status' => 200, 'message' => 'successfully notified.', 'data' => $allUsers]);
}
else
{
    return response()->json(['status' => 202, 'message' => 'No record found.', 'data' => array()]);
 
}       
    }
    public function duelrandom_pair(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'from_id' => 'required',
            'to_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => array(), 'message' => $validator->errors()]);
        }
       
        $quiz_type = QuizType::where('name', 'like', '%Duel%')->where('no_of_player', 2)->latest()->first();

        if (empty($quiz_type)) {
            return response()->json(['status' => 204, 'message' => 'Dual type quiz not found', 'data' => array()]);
        }

$ispaired =Challange::where('is_duelrandom','1')->where(function($query) use ($request) {
			$query->where('from_user_id', $request->from_id)
						->orWhere('to_user_id', $request->to_id);
})->first();
         

             if($ispaired)
             {
             $iscompleted = Attempt::where('id', $ispaired->attempt_id)->where('status', 'completed')->first();
              if(!$iscompleted)
             {
            return response()->json(['status' => 204, 'message' => 'user is already paired.', 'data' => array()]);
             }
             }
            
            
        $data = new Attempt;
        $data->user_id = $request->from_id;
        $data->quiz_type_id = $quiz_type->id;
        $data->difficulty_level_id = 3;
        $data->quiz_speed_id = 1;
        $data->isdualrandom = '1';

        $data->save();
        
         $data1 = new Attempt;
        $data1->user_id = $request->to_id;
        $data1->quiz_type_id = $quiz_type->id;
        $data1->difficulty_level_id = 3;
        $data1->quiz_speed_id = 1;
        $data1->parent_id = $data->id;
        $data1->isdualrandom = '1';

        $data1->save();
           
    
        $challange = new Challange;
        $challange->to_user_id = $request->to_id;
        $challange->from_user_id = $request->from_id;
        $challange->attempt_id = $data->id;
        $challange->status = '1';
        $challange->is_duelrandom = '1';
        $challange->save();

        // Create dual link

        $domain = new QuizDomain;
        $domain->attempts_id = $data->id;
        $domain->domain_id = implode(',', Domain::select('id')->pluck('id')->toArray());
        $domain->save();

        $quiztheme = new QuizTheme;
        $quiztheme->quiz_id = $data->id;
        $quiztheme->user_id = $request->from_id;
        $quiztheme->theme_id = 1;
        $quiztheme->save();

        $dual = [];
        $dual['duel_id'] = $data->id;
          $oldquizs = Duel_entry_room::where('user_id', $request->from_id)->first();
           if($oldquizs)
                     {
                  $oldquizs->deleted_at = date('Y-m-d h:i:s');
                     $oldquizs->save();
                     }
                     
            $oldquizs1 = Duel_entry_room::where('user_id', $request->to_id)->first();
           if($oldquizs1)
                     {
                  $oldquizs1->deleted_at = date('Y-m-d h:i:s');
                     $oldquizs1->save();
                     }   
                     
                               
        //$dualrandomquiz = Attempt::where('id',  $request->duel_id)->first();

        // Create dual link
        
        // $duel['domain'] = Domain::select('id', 'name')->get()->toArray();
        // $duel['quiz_speed'] = ucwords(strtolower($dualrandomquiz->quiz_speed->name));
        // $duel['difficulty'] = ucwords(strtolower($dualrandomquiz->difficulty->name));
        // $duel['quiz_type'] = ucwords(strtolower($dualrandomquiz->quiz_type->name));
        //  $dual['link']=Attempt::where('id',$data->id)->first()->link;
      //  $duel['created_date'] = date('d-M-Y', strtotime($dualrandomquiz->created_at));
       
      if( $challange)
      {
        return response()->json(['status' => 200, 'message' => 'Pair successfully Generated', 'data' => $dual]);
      }
      else
      {
        return response()->json(['status' => 202, 'message' => 'Pair not Generated', 'data' => array()]);

      }
    }
    public function exit_duelrandom(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => array(), 'message' => $validator->errors()]);
        }
          $ispaired =Challange::where('from_user_id', $request->user_id)->orWhere('to_user_id', $request->user_id)->where('is_duelrandom', '1')->first();
           if($ispaired)
                     {
                  $ispaired->deleted_at = date('Y-m-d h:i:s');
                     $ispaired->save();
                     }
         $oldquizs = Duel_entry_room::where('user_id', $request->user_id)->first();
           if($oldquizs)
                     {
                  $oldquizs->deleted_at = date('Y-m-d h:i:s');
                     $oldquizs->save();
                     }
                     if($oldquizs)
                     {
        return response()->json(['status' => 200, 'data' => '', 'message' => 'User Exit from room.']);
                     }
                     else
                     {
        return response()->json(['status' => 202, 'data' => array(), 'message' => 'No record found.']);            
                     }
    
         
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
            } else {
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
        $blockuser = BlockUser::where('blocked_by', $req->to_id)->where('blocked_to', $req->from_id)->first();
        if ($blockuser) {
            return response()->json(['status' => 201, 'data' => [], 'message' => "You can not send request to this user"]);
        }

        // Check if already request accepted by other user
        $checkifaccept =   Challange::where('attempt_id', $req->dual_id)
            ->where('status', '1')
            ->where('from_user_id', $req->from_id)->first();
        if ($checkifaccept) {
            if ($checkifaccept->to_user_id == $req->to_id) {
                $message = 'You are already sent the request';
            } else {
                $message = 'You cant not send the request at this point';
            }
            return response()->json(['status' => 201, 'data' => [], 'message' => $message]);
        }
        $challenge =   Challange::where('attempt_id', $req->dual_id)
            // ->where('status', '0')
            ->where('from_user_id', $req->from_id)->where('to_user_id', $req->to_id)->whereDate('created_at', carbon::now())->latest()->first();

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

            $attempt = Attempt::where('id', $challange->attempt_id)->first();
            $data = [
                'title' => 'Invitation received.',
                'token' => $challange->to_user->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'is_ios' => $challange->to_user->is_ios,
                //   'from'=>$challange->from_user->name,
                'message' => 'You have a new request from' . ' ' . $challange->from_user->name,
            ];
           //  $chat= Challange::create(['to_user_id'=>$req->to_id,'from_user_id'=>$req->to_id,'attempt_id'=>$req->dual_id,'status'=>'0','is_duelrandom'=>'0']);
             event(new \App\Events\RealTimeMessage($data));


            //notification

            // $attempt = Attempt::where('id', $chat->attempt_id)->first();
            // $data = [
            //     'title' => 'Invitation received.',
            //     'token' => $chat->to_user->token,
            //     'link' => $attempt->link,
            //     'type' => 'dual',
            //     'is_ios' => $chat->to_user->is_ios,
            //     //   'from'=>$challange->from_user->name,
            //     'message' => 'You have a new request from' . ' ' . $chat->from_user->name,
            // ];
            // sendNotification($data);

            // $savenoti = new FireBaseNotification;
            // $savenoti->user_id = $challange->to_user->id;
            // $savenoti->link = $attempt->link;
            // $savenoti->type = 'dual';
            // $savenoti->message = 'You have a new request from ' . ' ' . $challange->from_user->name;
            // $savenoti->title = 'Dual invitation send.';
            // $savenoti->status = '0';
            // $savenoti->save();

            return response()->json(['status' => 200, 'message' => 'Invitation Sent Successfully.']);
        }
        // }

    }
    public function accept_invitation(Request $req)
    {
        
         $user = User::where('id','2')->first();
           // event(new App\Events\RealTimeMessage('Hello World'));
           $data="Hello World";
           WebSocketsRouter::notification('\App\Notifications\RealTimeNotification')->listen(function($data,$notifiable,$notification){
                Log::info('Received'.get_class($notification)) ;
                Log::info('Received'.json_encode($data)) ;
          });
         //// $user->notify(new \App\Notifications\RealTimeNotification('Hello World','2'));
    
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
            return response()->json(['status' => 200, 'message' => 'Sorry! No duel quiz found.']);
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
                $data2 =  Attempt::where('id', $data->parent_id)->withTrashed()->first();
            } else {
                $data2 =  Attempt::where('parent_id', $request->dual_id)->withTrashed()->first();
        
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
            $user['percentage'] = ($user_data->result) ? $user_data->result : 0;
            if (isset($user_data->user->profile_image)) {
                $user['image']  = url('/storage') . '/' . $user_data->user->profile_image;
            } else {
                $user['image']  = '';
            }
            if ($user_data->status == 'completed') {
                $user['is_submit'] = '1';
            } else {
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
            if ($user['percentage'] > $response['percentage']) {
                $res[] = $user;
                $res[] = $response;
            } else {
                $res[] = $response;
                $res[] = $user;
            }

            $relaseuser = CheckUserState::where('user_id', $request->user_id);
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
            $dual['name'] = $challenge->to_user->name;
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

        $quiz_rules = QuizRule::select('more')->where('quiz_type_id', 2)->where('quiz_speed_id', $data2->quiz_speed_id)->where('status', '1')->first();

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
                'title' => 'Duel invitation rejected.',
                'token' => User::where('id', $attempt->user_id)->first()->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'is_ios' => User::where('id', $attempt->user_id)->first()->is_ios,
                'message' => User::where('id', $req->user_id)->first()->name . " had  rejected the request",
            ];
            sendNotification($data);
            return response()->json(['status' => 200, 'data' => [], 'message' => 'No invitation']);
        } else {
            // $challenge->deleted_at = date('Y-m-d h:i:s');
            $challenge->status = '2';
            $challenge->save();


            $data = [
                'title' => 'Duel invitation rejected.',
                'token' => $challenge->from_user->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'is_ios' => $challenge->from_user->is_ios,
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


        $data = Attempt::where('link', $request->dual_link)->where('started_at', null)->first();
        if (empty($data)) {
            return response()->json(['status' => 204, 'message' => 'Sorry! Link had expired. or not found']);
        }

        $domain =  QuizDomain::where('attempts_id', $data->id)->first()->domain_id;

        $domains = explode(',', $domain);

        $dual = [];
        if ($data->quiz_type_id == 3) {
            $dual['quiz_room_id'] = $data->id;
        } else {
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


    public function duelrank(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'dual_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $data = Attempt::where('id', $request->dual_id)->where('quiz_type_id', '2')->first();
       if($data->parent_id){
            $data = Attempt::where('id', $data->parent_id)->where('quiz_type_id', '2')->first();
       }
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }
       
        $totalusers = Attempt::where('id', $data->id)->orWhere('parent_id', $data->id)->orderBy('marks', 'DESC')->get();
        
        $count = 0;
      
        foreach ($totalusers as $checsubmit){
            if($checsubmit->end_at != null){
             $count++;
            }
        }
        $rankdata = [];
        $res=[];
        $quizspeed = QuizSpeed::find($data->quiz_speed_id);
        if ($quizspeed->quiz_speed_type == 'single') {
            $totaltime = $quizspeed->no_of_question * $quizspeed->duration;
        } else {
            $totaltime = $quizspeed->duration;
        }

        $totaltime = $totaltime+45; // Delay of 45 seconds
        //    dd($totaltime);
        if ($data->started_at) {
            $endtime =  Carbon::parse($data->started_at)
                ->addSeconds($totaltime)
                ->format('Y-m-d H:i:s');
        } else {

            $endtime =  Carbon::parse($data->created_at)
                ->addSeconds($totaltime)
                ->format('Y-m-d H:i:s');
        }

        if ($count == 2) {
            foreach ($totalusers as $user) {
                $rankdata[$user->user_id] = $user->marks;
                arsort($rankdata);
            }
        } else {
           
            $nowDate = Carbon::now();
            $result = $nowDate->gt($endtime);
            if ($result) {
                foreach ($totalusers as $user) {
                    $rankdata[$user->user_id] = $user->marks;
                    arsort($rankdata);
                }
            }
        }

        if ($rankdata) {
            $rank = 1;
            $myrank = 0;
            $message = '';
            $olddata = '';
           
            foreach ($rankdata as $key => $rankdata) {
                if ($olddata == $rankdata) {
                    $rank--;
                }
                if ($key == $request->user_id) {
                    $myrank = $rank;
                    if ($myrank == 1) {
                        $message = 'You won the quiz.';
                    }
                }
               
                
                    $rank++;
                $olddata = $rankdata;

               
            }
            $userdata = User::find($request->user_id);
        
                if($myrank==1){
                            $res['image']  = url('/storage') . '/' . $userdata->profile_image;
                            $res['name']  = $userdata->name;
                            $res['rank']  = $myrank;
                            $res['message']  = $message;
                } 
                
            // $endtime =
            // Carbon::parse($endtime)
            //     ->format('H:i:s');
                if($res){
            return response()->json(['status' => 200,'time' => $endtime, 'completed' => '1', 'data' => $res, 'message' => 'Rank Data']);
        }  else{
                return response()->json(['status' => 200, 'time' => $endtime, 'completed' => '1',  'message' => 'Second user rank']);

        } 
    }
        else {
            
            return response()->json(['status' => 201, 'time'=> $endtime, 'completed'=>'0',  'message' => 'Result not calculated yet']);
        }
    }
}