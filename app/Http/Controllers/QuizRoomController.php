<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Attempt;
use App\Challange;
use App\QuizDomain;
use App\AgeGroup;
use App\User;
use App\Contact;
use App\BlockUser;
use App\QuizType;
use App\Domain;
use App\FireBaseNotification;

class QuizRoomController extends Controller
{
    //

    public function create_quiz_room(Request $request)
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
        $quiz_type = QuizType::where('name', 'like', '%Quiz Room%')->latest()->first();

        if (empty($quiz_type)) {
            return response()->json(['status' => 204, 'message' => 'Quiz Room type quiz not found', 'data' => array()]);
        }
        $data = new Attempt;
        $data->user_id = $request->user_id;
        $data->quiz_type_id = $quiz_type->id;
        $data->difficulty_level_id = $request->difficulty_level_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->save();

        // Create dual link
        $quiz_room = Attempt::where('id', $data->id)->first();
        $quiz_room->link = "cul.tre/quiz_room#" . $data->id;
        $quiz_room->save();

        $domain = new QuizDomain;
        $domain->attempts_id = $data->id;
        $domain->domain_id = $request->domains;
        $domain->save();

        $room = [];
        $room['quiz_room'] = $data->id;
        $room['user'] = ucwords(strtolower($data->user->name));
        $domains = explode(',', $request->domains);
        $room['domain'] = Domain::select('id', 'name')->whereIn('id', $domains)->get()->toArray();
        $room['quiz_speed'] = ucwords(strtolower($data->quiz_speed->name));
        $room['difficulty'] = ucwords(strtolower($data->difficulty->name));
        $room['quiz_type'] = ucwords(strtolower($data->quiz_type->name));
        $room['created_date'] = date('d-M-Y', strtotime($data->created_at));

        return response()->json(['status' => 200, 'message' => 'Quiz Romm quiz Created', 'data' => $room]);
    }

    
}
