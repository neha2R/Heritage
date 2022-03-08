<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Goal;
class GoalController extends Controller
{
    //
    public function setgoal(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
       $ifset = Goal::whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->first();
        if($ifset){
            return response()->json(['status' => 201, 'message' => 'Goal already set for current month', 'data' => []]);

        }else{
            $savedata = new Goal;
            $savedata->user_id = $request->user_id;
            $savedata->type=$request->type;
            $savedata->no=$request->no;
            $savedata->save();
            return response()->json(['status' => 200, 'message' => 'Goal set succesfully', 'data' => $savedata]);

        }
    }
}
