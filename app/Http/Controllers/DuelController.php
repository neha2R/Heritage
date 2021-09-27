<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attempt;
use App\Challange;
use App\QuizDomain;
use App\AgeGroup;
use App\Country;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\QuizType;
use App\Domain;

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
        $quiz_type = QuizType::where('name','like','%Dual%')->where('no_of_player',2)->latest()->first();
        
        if(empty($quiz_type)){
      return response()->json(['status' => 204, 'message' => 'Dual type quiz not found','data' => array()]);
        }
        $data = new Attempt;
        $data->user_id = $request->user_id;
        $data->quiz_type_id = $quiz_type->id;
        $data->difficulty_level_id = $request->difficulty_level_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->save();

        // Create dual link
        $dual=Attempt::where('id',$data->id)->first();
        $dual->link="cul.tre/dual#".$data->id;
        $dual->save();

        $domain = new QuizDomain;
        $domain->attempts_id = $data->id;
        $domain->domain_id = $request->domains;
        $domain->save();
        
          $dual=[];
          $dual['dual_id']= $data->id;
         $dual['user']=ucwords(strtolower($data->user->name));
         $domains = explode(',',$request->domains);
         $dual['domain']=Domain::select('id','name')->whereIn('id',$domains)->get()->toArray();
         $dual['quiz_speed']=ucwords(strtolower($data->quiz_speed->name));
         $dual['difficulty']=ucwords(strtolower($data->difficulty->name));
         $dual['quiz_type']=ucwords(strtolower($data->quiz_type->name));
        //  $dual['link']=Attempt::where('id',$data->id)->first()->link;
         $dual['created_date']=date('d-M-Y',strtotime($data->created_at));

        return response()->json(['status' => 200, 'message' => 'Dual Created','data' => $dual]);
     }

     public function get_all_users(Request $req)
     {
        $validator = Validator::make($req->all(), [
            'dual_id' => 'required',
            'user_id'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
             $users=User::where('id','!=',$req->user_id)->get(); 
             $data=[];
             foreach($users as $user)
             {
                $age=Carbon::parse($user->dob)->age;
              
                $allUsers['name']=ucwords(strtolower($user->name));

                if($ageGroup=AgeGroup::where('from','<=',$age)->where('to','>=',$age)->first())
                {
                    $allUsers['age_group']=ucwords(strtolower($ageGroup->name));
                }
                else
                {
                    $allUsers['age_group']="";
                }
                $allUsers['flag_icon']=public_path('/flags/').strtolower($user->country->sortname).".png";
                $allUsers['status']="Online";
                if(Challange::where('attempt_id',$req->dual_id)->where('to_user_id',$user->id)->whereDate('created_at',carbon::now())->first())
                {
                    $allUsers['request']="1";
                }
                else
                {
                    $allUsers['request']="0";
                }
                
                $data[]=$allUsers;
                    
             }
             

   
             return response()->json(['status' => 200, 'message' => 'all users','data' => $data]);   
     }
     public function send_invitation(Request $req)
     {
        $validator = Validator::make($req->all(), [
            'from_id' => 'required',
            'dual_id'=>'required',
            'to_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
     $challenge=   Challange::where('attempt_id',$req->dual_id)
      ->where('status','0')
     ->where('from_user_id',$req->from_id)->whereDate('created_at',carbon::now())->latest();

        if(carbon::now()->parse($challenge->created_at)->diffInSeconds()<60)
        {
           return response()->json(['status' => 200, 'message' => 'Sorry! Wait for 1 minutes or till accept the request.']);   
        }

        // if($challenge)
        // {
        //         return response()->json(['status' => 422, 'data' => '', 'message' => "Sorry You have already sent this user request for the dual quiz."]);
        // }
        // else
        // {
            $challange=Challange::where('attempt_id',$req->dual_id)->where('from_user_id',$req->from_id)
            ->where('to_user_id',$req->to_id)
            ->whereDate('created_at',carbon::now())->get()->count();
            if($challange>=3)
            {
                return response()->json(['status' => 422, 'data' => '', 'message' => "Sorry You can not send invitations more then 3 Users in single day."]);
            }
            else
            {
                  $challange=new Challange;
                  $challange->to_user_id=$req->to_id;
                  $challange->from_user_id=$req->from_id;
                  $challange->attempt_id=$req->dual_id;
                  $challange->save();
                 
                  //notification
                 
                 $attempt=Attempt::where('id',$challange->attempt_id)->first();
                  $data=[
                      'title'=>'Invitation recieved.',
                      'token'=>$challange->to_user->token,
                      'link'=>$attempt->link,
                    //   'from'=>$challange->from_user->name,
                      'message'=>'You have a new request from'.$challange->from_user->name,
                  ];
                  sendNotification($data);
                  return response()->json(['status' => 200, 'message' => 'Invitation Sent Successfully.']);   
            }
        // }
       
     }
     public function accept_invitation(Request $req)
     {
      
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'dual_link'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

       
         $attempt=Attempt::where('link',$req->dual_link)->first();
         if(empty($attempt)){
            return response()->json(['status' => 204, 'message' => 'Sorry! Link has been expired. or not found']); 
         }
         $challenge=Challange::where('attempt_id',$attempt->id)->where('to_user_id',$req->user_id)->first();

         if(empty($challenge)){
            return response()->json(['status' => 204, 'message' => 'Invitation not send yet']); 
         }
         if(carbon::now()->parse($challenge->created_at)->diffInSeconds()>60)
         {
            return response()->json(['status' => 200, 'message' => 'Sorry! Invitation has been expired.']);   
         }
         else
         {
            
            if($attempt->challange_id!="")
            {
               return response()->json(['status' => 422, 'data' => '', 'message' => 'somebody has already accepted the request. try next time!']);
            }
            else
            {

               $attempt->challange_id=$req->user_id;
               $attempt->save();

               $data=[
                'title'=>'Invitation accepted.',
                'token'=>$challenge->from_user->token,
                'link'=>$attempt->link,
                'message'=>User::where('id',$req->user_id)->first()->name." has been accepted the request. you can start quiz now",
                ];
                sendNotification($data);
               return response()->json(['status' => 200, 'message' => 'Invitation Successfully accepted.']);   

            }
         }
     }
     public function generate_link(Request $req)
     {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'dual_id'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        
        if($attempt=Attempt::where('user_id',$req->user_id)->where('id',$req->dual_id)->first())
        {
       
            $data=[];
            $data['link']=$attempt->link;
            return response()->json(['status' => 200, 'message' => 'Generated Link','data'=>$data]);   
        }
        else
        {
            return response()->json(['status' => 200, 'message' => 'Sorry! No dual quiz found.']);  
        }
     }
}
