<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Goal;
use App\Attempt;
use Carbon\Carbon;

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
       $ifset = Goal::where('user_id',$request->user_id)->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->latest()->first();
        if($ifset){
            return response()->json(['status' => 201, 'message' => 'Goal already set for current month', 'data' => []]);

        }
        else
        {
            $savedata = new Goal;
            $savedata->user_id = $request->user_id;
            $savedata->type=$request->type;
            $savedata->no=$request->no;
            $savedata->save();
            return response()->json(['status' => 200, 'message' => 'Goal set succesfully', 'data' => $savedata]);

        }
    }


    public function goalsummary(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $check = Goal::where('user_id', $request->user_id)->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->latest()->first();
            if(!$check){
        return response()->json(['status' => 201, 'message' => 'Please set goal', 'data' => []]);
            }
        $totalquiz = 0;
           if($check->type=='daily'){
            $totalquiz = Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->whereDate('created_at', Carbon::today())->first()->totalquiz;

           }
        if ($check->type == 'weekely') {
            $totalquiz = Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->first()->totalquiz;

        }
        if ($check->type == 'monthly') {
            $totalquiz = Attempt::selectRaw("Count(id) as totalquiz")->where('user_id', $request->user_id)->where('status', 'completed')->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))->first()->totalquiz;

        }
        $data['total'] = $check->no;
        $data['play'] = $totalquiz;
        return response()->json(['status' => 200, 'message' => 'Goal set succesfully', 'data' => $data]);

    }
}
