<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
           $xps= Attempt::selectRaw("SUM(xp) as xp")->where('user_id', $request->user_id)->whereMonth('created_at', $key + 1)->whereYear('created_at', date('Y'))->first()->xp;
            if ($xps == 0) {
                $xps = 0;
            }
           
           $xp['xp'] = $xps ;
            $xp['month'] = $month;
            
            $sum += $xp['xp'];
            $data['mnth'][] = $xp;
        }
        $data['totalxp'] = $sum;
        $totalquiz= Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->first()->totalquiz;
        if(!$totalquiz){
            $totalquiz=0;
        }
        $data['totalquiz'] = $totalquiz;

        return response()->json(['status' => 200, 'message' => 'xp', 'data' => $data]);

    }

    

}
