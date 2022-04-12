<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\UserBadge;
use App\Badge;
use App\User;
use Carbon\Carbon;
use App\AgeGroup;
use App\Contact;
use App\League;
use App\UserLeagueWithPer;
use App\MonthendXp;

class ProfileController extends Controller
{
    public function xpgainchart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $sum=0;
        $data=[];
        $check = true;
        if ($request->contact_id) {
            $setting =  userProfileSetting($request->user_id);
            if ($setting == 'all') {
                $check = true;
            } else {
                $check = false;
            }
        }

        if (!$check) {
            return response()->json(['status' => 201, 'message' => 'Profile set not visible to all', 'data' => $data]);
        }
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
       
        foreach ($months as $key => $month) {

         
                 $key = $key+1;
            // Badges xp calculate
            $badgeids = UserBadge::where('user_id', $request->user_id)->whereMonth('created_at', $key)->whereYear('created_at', date('Y'))->pluck('badge_id')->toArray();
      
            $xpofbadges = Badge::whereIn('id', $badgeids)->sum('xp');
                // Quizzes xp calculate
           $xps= Attempt::selectRaw("SUM(xp) as xp")->where('user_id', $request->user_id)->whereMonth('created_at', $key)->whereYear('created_at', date('Y'))->first()->xp;
          // Tournament month end lp to xp 
           $monthendxp = MonthendXp::where('user_id', $request->user_id)->whereMonth('created_at', $key)->whereYear('created_at', date('Y'))->first();
           if ($xps == 0) {
                $xps = "0";
            }
       if($monthendxp){
                $monthendx=   $monthendxp->xp;
       }else{
                $monthendx=0;
       }
           $xp['xp'] = $xps+ $xpofbadges + $monthendx;
            $xp['month'] = $month;
            
            $sum += $xp['xp'];
            $data['mnth'][] = $xp;
        }
      $max = max($data['mnth']);
       $data['totalxp'] = $sum;
        $data['max'] = $max['xp'];
        $totalquiz= Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->first()->totalquiz;
        if(!$totalquiz){
            $totalquiz=0;
        }
        $data['totalquiz'] = $totalquiz;

        return response()->json(['status' => 200, 'message' => 'xp', 'data' => $data]);

    }


    public function user_profile(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $contactdata = array();
        $userdata=[];
        if($request->contact_id){
           $contact= User::find($request->contact_id);
            $age = Carbon::parse($contact->dob)->age;
            $contactdata['id'] = $contact->id;
            $contactdata['name'] = ucwords(strtolower($contact->name));

            if ($ageGroup = AgeGroup::where('from', '<=', $age)->where('to', '>=', $age)->first()) {
                $contactdata['age_group'] = ucwords(strtolower($ageGroup->name));
            } else {
                $contactdata['age_group'] = "";
            }
            if ($contact->country) {
                $contactdata['country'] = $contact->country->country_name->name;
                $contactdata['flag_icon'] = url('/flags') . '/' . strtolower($contact->country->country_name->sortname) . ".png";
            } else {
                $contactdata['flag_icon'] = url('/flags/') . strtolower('in') . ".png";
            }
            $contactdata['status'] = "Online";
            if (isset($contact->profile_image)) {
                $contactdata['image'] = url('/storage') . '/' . $contact->profile_image;
            } else {
                $contactdata['image'] = '';
            }

        }
        $user = User::find($request->user_id);

        $age = Carbon::parse($user->dob)->age;
        $userdata['id'] = $user->id;
        $userdata['name'] = ucwords(strtolower($user->name));

        if ($ageGroup = AgeGroup::where('from', '<=', $age)->where('to', '>=', $age)->first()) {
            $userdata['age_group'] = ucwords(strtolower($ageGroup->name));
        } else {
            $userdata['age_group'] = "";
        }
        if ($user->country) {
            $userdata['country'] = $user->country->country_name->name;
            $userdata['flag_icon'] = url('/flags') . '/' . strtolower($user->country->country_name->sortname) . ".png";
        } else {
            $userdata['flag_icon'] = url('/flags/') . strtolower('in') . ".png";
        }
        $userdata['status'] = "Online";

        if (isset($user->profile_image)) {
            $userdata['image'] = url('/storage') . '/' . $user->profile_image;
        } else {
            $userdata['image'] = '';
        }
        $is_friend = 0;
        // Check if already friend or not
        $oldFriend = Contact::where('friend_one', $request->user_id)->where('friend_two', $request->contact_id)->first();
        if (!isset($oldFriend)) {
            $oldFriend = Contact::where('friend_one', $request->contact_id)->where('friend_two', $request->user_id)->first();
        }

        if(isset($oldFriend)){
            $is_friend = 1;  
        }

        // League of a user
        $userleague = UserLeagueWithPer::where('user_id', $request->user_id)->first();

        if (empty($userleague)) {
            $your_leage['league_id'] = 5;
            $your_leage['league_name'] = 'Initiate';
        } else {
            $your_leage['league_id'] = $userleague->league_id;
            $your_leage['league_name'] = League::find($userleague->league_id)->title;
        }

        $response['user'] = $userdata;
        $response['contact'] =  $contactdata ? $contactdata :json_encode($contactdata, JSON_FORCE_OBJECT);
        $response['is_friend'] = $is_friend;
        $response['your_league'] = $your_leage;
        
        return response()->json(['status' => 200, 'message' => 'Profile data', 'data' => $response]);

    }
    

}
