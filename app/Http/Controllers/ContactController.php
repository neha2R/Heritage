<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use App\Challange;
use App\AgeGroup;
use App\BlockUser;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }


    // ======= API function start here

    /** 
     * Add to contact of user using mobile contact
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import_contact(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'mobiles' => 'required|json',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $mobiles = json_decode($request->mobiles);
      
         $userDatas = User::whereIn('mobile',$mobiles)->where('type','2')->get();

         $data=[];

         if($userDatas->count()>0){
            foreach($userDatas as $userData){
              $oldFriend = Contact::where('friend_one',$request->user_id)->where('friend_two',$userData->id)->first();
            //    dd($oldFriend);
              if(!isset($oldFriend)){
                $savedata = new Contact;
                $savedata->friend_one = $request->user_id;
                $savedata->friend_two = $userData->id;
                $savedata->invited_via = 'mobile';
                $savedata->status = '1';
                $savedata->save();
               
                $data[] = $userData->name;
              }

            }
            if(empty($data)){
                return response()->json(['status' => 200, 'data' => '', 'message' => 'No new user found']);
            }else{
            return response()->json(['status' => 200, 'data' => $data, 'message' => 'New user added to your friend list']);
            }
         }else{
            return response()->json(['status' => 200, 'data' => '', 'message' => 'User not found']);
         }
        
    }



    /** 
     * Get all contact of user
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchContacts($id){
    $totalfiends = Contact::where('friend_one',$id)->pluck('friend_two')->toArray();
    $blockuser = BlockUser::where('blocked_by',$id)->pluck('blocked_to')->toArray();

    $onlyfriends = array_diff($totalfiends, $blockuser);

 $users = User::whereIn('id',$onlyfriends)->get();
    foreach($users as $user)
    {
       $age=Carbon::parse($user->dob)->age;
       $allUsers['id']=$user->id;
       $allUsers['name']=ucwords(strtolower($user->name));

       if($ageGroup=AgeGroup::where('from','<=',$age)->where('to','>=',$age)->first())
       {
           $allUsers['age_group']=ucwords(strtolower($ageGroup->name));
       }
       else
       {
           $allUsers['age_group']="";
       }
       if($user->country){
       $allUsers['flag_icon']=url('/flags').'/'.strtolower($user->country->sortname).".png";
       } else{
           $allUsers['flag_icon']=url('/flags/').strtolower('in').".png"; 
       }
       $allUsers['status']="Online";
       
       
       $data[]=$allUsers;
           
    }
    if(empty($data)){
        return response()->json(['status' => 200, 'data' => '', 'message' => 'No  user found']);
    }
    else{
    return response()->json(['status' => 200, 'data' => $data, 'message' => 'All your contact list']);
    }

 }



}
