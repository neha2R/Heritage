<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\UserBadge;
use App\Badge;
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
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
       
        foreach ($months as $key => $month) {

         
                 $key = $key+1;
            // Badges xp calculate
            $badgeids = UserBadge::where('user_id', $request->user_id)->whereMonth('created_at', $key)->pluck('badge_id')->toArray();
      
            $xpofbadges = Badge::whereIn('id', $badgeids)->sum('xp');

           $xps= Attempt::selectRaw("SUM(xp) as xp")->where('user_id', $request->user_id)->whereMonth('created_at', $key)->whereYear('created_at', date('Y'))->first()->xp;
            if ($xps == 0) {
                $xps = "0";
            }
       
           $xp['xp'] = $xps+ $xpofbadges ;
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

    

}
