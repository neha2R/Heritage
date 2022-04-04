<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Badge;
use App\UserBadge;
use App\Attempt;
use App\QuizTheme;

class BadgeController extends Controller
{
    //

    public function userbadges(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $badges =  UserBadge::where('user_id', $request->user_id)->get();
        $res = [];
        $check= true;
        if($request->contact_id){
          $setting=  userProfileSetting($request->user_id);
       if($setting=='all'){
                $check =true;
       }else{
                $check =false;
       }
        }
        
        if($check){
           
        foreach ($badges as $badge) {
            $data['title']  = $badge->badgedata->title;
            $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $badge->badgedata->image;
            $data['description'] = $badge->badgedata->description;
            $data['id']  = $badge->badgedata->id;
            $res[] = $data;
        }
      }
        return response()->json(['status' => 200, 'message' => 'Badge data', 'data' => $res]);
    }

    public function checkbadge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $totalquiz = Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->first()->totalquiz;
        // Check Quiz Badge
   
        $checkbadge = Badge::where('no', $totalquiz)->where('type', 'quiz')->first();

        $data = [];
        $ifalready='';
        if ($checkbadge) {
            $ifalready = UserBadge::where('badge_id', $checkbadge->id)->first();
           
            if (!$ifalready) {
                $savebadge = new UserBadge;
                $savebadge->user_id = $request->user_id;
                $savebadge->badge_id = $checkbadge->id;
                $savebadge->save();

                $data['title']  = $checkbadge->title;
                $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $checkbadge->image;
                $data['description'] = $checkbadge->description;
            }
        }

        $quizs = Attempt::where('user_id', $request->user_id)->where('status', 'completed')->pluck('id')->toArray();
        $completedQuizs =  QuizTheme::whereIn('quiz_id', $quizs)->get();
        $one = 0;
        $two = 0;
        $three = 0;
        foreach ($completedQuizs as $quiz) {
            $themes = explode(',', $quiz->theme_id);
            if (in_array('1', $themes)) {
                $one = $one + 1;
            }
            if (in_array('2', $themes)) {
                $two = $two + 1;
            }
            if (in_array('3', $themes)) {
                $three = $three + 1;
            }
        }
        $ih = Badge::where('no', $one)->where('type', 'ih')->first();
        $nh = Badge::where('no', $two)->where('type', 'nh')->first();
        $th = Badge::where('no', $three)->where('type', 'th')->first();

        if ($ih) {
            $ifalready = UserBadge::where('badge_id', $ih->id)->first();
            if (!$ifalready) {
                $savebadge = new UserBadge;
                $savebadge->user_id = $request->user_id;
                $savebadge->badge_id = $ih->id;
                $savebadge->save();

                $data['title']  = $ih->title;
                $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $ih->image;
                $data['description'] = $ih->description;
            }
        }

        if ($nh) {
            $ifalready = UserBadge::where('badge_id', $nh->id)->first();
            if (!$ifalready) {
                $savebadge = new UserBadge;
                $savebadge->user_id = $request->user_id;
                $savebadge->badge_id = $nh->id;
                $savebadge->save();

                $data['title']  = $nh->title;
                $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $nh->image;
                $data['description'] = $nh->description;
            }
        }

        if ($th) {
            $ifalready = UserBadge::where('badge_id', $th->id)->first();
            if (!$ifalready) {
                $savebadge = new UserBadge;
                $savebadge->user_id = $request->user_id;
                $savebadge->badge_id = $th->id;
                $savebadge->save();

                $data['title']  = $th->title;
                $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $th->image;
                $data['description'] = $th->description;
            }
        }
        if($ifalready){
            $badge = Badge::find($ifalready->badge_id);
            $data['title']  = $badge->title;
            $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $badge->image;
            $data['description'] = $badge->description;  
        }
        return response()->json(['status' => 200, 'message' => 'Badge recived', 'data' => $data]);

    }


    public function badges_details(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'badges_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $userbadge =  UserBadge::where('user_id', $request->user_id)->where('badge_id',$request->badges_id)->first();
        $badge = Badge::where('id', $request->badges_id)->first();
         $res = [];
        $res= json_encode($res, JSON_FORCE_OBJECT);
         if(!isset($badge)){
        return response()->json(['status' => 200, 'message' => 'Badge not found', 'data' => $res]);
         }
        // foreach ($badges as $badge) {
            $data['title']  = $badge->title;
            $data['image'] = url('/storage/badgesimages/fourhundred') . '/' . $badge->image;
            $data['description'] = $badge->description;
            if(isset($userbadge->badge_id)){
          if($userbadge->badge_id==$request->badges_id){
            $data['won'] = '1';
            $data['message']='You won this badge on '.date('d-m-Y',strtotime($userbadge->created_at));
          
        }
      }else{
              $data['won']='0';
            $data['message']='You haven`t won this badge ! ';

          }
             $res = $data;
        // }
        return response()->json(['status' => 200, 'message' => 'Badge data', 'data' => $res]);

    }
}
