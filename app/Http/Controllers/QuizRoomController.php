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
use App\QuizRule;
use Carbon\Carbon;
use App\Jobs\SaveQuizRoomResult;
use App\Performance;

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
        $quiz_room->link = "cul.tre/quizroom#" . $data->id;
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

    public function quiz_rules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_room_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $data = Attempt::where('id', $request->quiz_room_id)->where('quiz_type_id', 3)->first();
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }


        $quiz_rules = QuizRule::select('scoring', 'negative_marking', 'time_limit', 'no_of_players', 'hint_guide', 'que_navigation', 'more')->where('quiz_type_id', 3)->where('quiz_speed_id', $data->quiz_speed_id)->first();

        if (empty($quiz_rules)) {
            return response()->json(['status' => 204, 'message' => 'No rules found for the quiz', 'data' => []]);
        } else {
            // $data = json_decode($quiz_rules->more);
            $quiz_rules->more = json_decode($quiz_rules->more);
            $data = $quiz_rules->toArray();
            $data = array_filter(array_values($data));
            return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
        }
    }

    public function disband_quiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_room_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $data = Attempt::where('id', $request->quiz_room_id)->where('quiz_type_id', 3)->first();
        if (!$data) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found']);
        }
        $data->deleted_at = date('Y-m-d h:i:s');
        $data->save();
        Challange::where('attempt_id', $request->quiz_room_id)->update(['deleted_at' => date('Y-m-d h:i:s')]);


        return response()->json(['status' => 200, 'message' => 'Quiz disbanded succesfully', 'data' => $data]);
    }

    public function send_invitation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'from_id' => 'required',
            'quiz_room_id' => 'required',
            'to_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $challenge =   Challange::where('attempt_id', $req->quiz_room_id)
            ->where('from_user_id', $req->from_id)->first();


        if ($challenge) {
            return response()->json(['status' => 422, 'data' => '', 'message' => "Sorry You have already sent this user request for the quiz room quiz."]);
        }
        // else
        // {
        // $challange = Challange::where('attempt_id', $req->dual_id)->where('from_user_id', $req->from_id)
        //     ->where('to_user_id', $req->to_id)
        //     ->whereDate('created_at', carbon::now())->get()->count();
        // if ($challange >= 3) {
        //     return response()->json(['status' => 422, 'data' => '', 'message' => "Sorry You can not send invitations to a single user more then 3 times in a day."]);
        // } else {
        $challange = new Challange;
        $challange->to_user_id = $req->to_id;
        $challange->from_user_id = $req->from_id;
        $challange->attempt_id = $req->quiz_room_id;
        $challange->status = '0';
        $challange->save();

        //notification

        $attempt = Attempt::where('id', $challange->attempt_id)->first();
        $data = [
            'title' => 'Invitation send.',
            'token' => $challange->to_user->token,
            'link' => $attempt->link,
            'type' => 'quizroom',
            //   'from'=>$challange->from_user->name,
            'message' => 'You have a new request from' . $challange->from_user->name,
        ];
        sendNotification($data);

        $savenoti = new FireBaseNotification;
        $savenoti->user_id = $challange->to_user->id;
        $savenoti->link = $attempt->link;
        $savenoti->type = 'quizroom';
        $savenoti->message = 'You have a new request from' . $challange->from_user->name;
        $savenoti->title = 'Quiz room Invitation send.';
        $savenoti->status = '0';
        $savenoti->save();

        return response()->json(['status' => 200, 'message' => 'Invitation Sent Successfully.']);
        // }
        // }

    }

    public function accept_invitation(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'room_link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $attempt = Attempt::where('link', $req->room_link)->first();
        if (empty($attempt)) {
            return response()->json(['status' => 204, 'message' => 'Sorry! Link has been expired. or not found']);
        }
        if (Challange::where('attempt_id', $attempt->id)->where('status', '1')->count >= 10) {
            return response()->json(['status' => 422, 'data' => '', 'message' => 'Sorry Max limit of Quiz player exceed']);
        }
        $challenge = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $req->user_id)->latest()->first();

        // If user come via link
        if (empty($challenge)) {
            $challenge = new Challange;
            $challenge->to_user_id = $req->user_id;
            $challenge->from_user_id = $attempt->user_id;
            $challenge->attempt_id = $attempt->id;
            $challenge->status = '1';
            $challenge->save();
            // return response()->json(['status' => 204, 'message' => 'Invitation not send yet to user']);
        }

        if (carbon::now()->parse($challenge->created_at)->diffInSeconds() > 180) {
            return response()->json(['status' => 200, 'message' => 'Sorry! Invitation has been expired.']);
        } else {
            $data = [
                'title' => 'Quiz Room Invitation accepted.',
                'token' => $challenge->from_user->token,
                'link' => $attempt->link,
                'type' => 'quizroom',
                'message' => User::where('id', $req->user_id)->first()->name . " has been accepted the request",
            ];
            // Create new data for user who accepts the request

            // $acceptuser = new Attempt;
            // $acceptuser->user_id = $req->user_id;
            // $acceptuser->parent_id = $attempt->id;
            // $acceptuser->difficulty_level_id = $attempt->difficulty_level_id;
            // $acceptuser->quiz_type_id = $attempt->quiz_type_id;
            // $acceptuser->quiz_speed_id = $attempt->quiz_speed_id;
            // $acceptuser->save();

            // Update challange table status to accepted
            $challenge->status = '1';
            $challenge->save();

            sendNotification($data);
            // Save notification
            $savenoti = new FireBaseNotification;
            $savenoti->user_id = $challenge->from_user->id;
            $savenoti->link = $attempt->link;
            $savenoti->type = 'quizroom';
            $savenoti->message = User::where('id', $req->user_id)->first()->name . " has been accepted the request. you can start quiz now";
            $savenoti->title = 'Quiz room Invitation accepted.';
            $savenoti->status = '0';
            $savenoti->save();

            // $response['quiz_id'] = $acceptuser->id;

            return response()->json(['status' => 200, 'data' => $attempt->id, 'message' => 'Invitation Successfully accepted.']);
        }
    }

    public function generate_link(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'room_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        if ($attempt = Attempt::where('user_id', $req->user_id)->where('id', $req->room_id)->first()) {

            $data = [];
            $data['link'] = $attempt->link;
            return response()->json(['status' => 200, 'message' => 'Generated Link', 'data' => $data]);
        } else {
            return response()->json(['status' => 200, 'message' => 'Sorry! No dual quiz found.']);
        }
    }

    public function room_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $attempt = Attempt::where('id', $request->room_id)->first();
        if (!isset($attempt)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found..']);
        }
        $userids = Challange::where('attempt_id', $attempt->id)->where('status', '1')->pluck('to_user_id')->toArray();
        $users = User::whereIn('id', $userids)->get();
        $data = [];
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

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Quiz room users list']);
    }


    public function delete_user_room(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $attempt = Attempt::where('id', $request->room_id)->first();

        if (!isset($attempt)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found..']);
        }
        $user = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $request->user_id)->first();
        if (!isset($user)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'User not in the quiz']);
        }
        $user->delete();
        return response()->json(['status' => 200, 'data' => [], 'message' => 'User removed from room']);
    }

    public function leaveroom(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $attempt = Attempt::where('id', $request->room_id)->first();

        if (!isset($attempt)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Quiz not found..']);
        }
        $user = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $request->user_id)->first();
        if (!isset($user)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'User not in the quiz']);
        }
        $user->delete();
        return response()->json(['status' => 200, 'data' => [], 'message' => 'User removed from room']);
    }


    public function save_room_result(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required',
            'user_id' => 'required',
            'quiz_answer'=>'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $quiz = Attempt::find($request->quiz_id);
        if (!empty($quiz)) {

            if ($quiz->user_id != $request->user_id) {
                // Check if user register with room
                $user = Challange::where('attempt_id', $quiz->id)->where('to_user_id', $request->user_id)->where('status','1')->first();
             if(empty($user)){
                    return response()->json(['status' => 201, 'data' => [], 'message' => 'Not a valid user']);
  
             }
                $userquiz = Attempt::where('parent_id',$request->quiz_id)->where('user_id', $request->user_id)->first();
            if(empty($userquiz)){
                    $userquiz = new Attempt;
                    $userquiz->user_id = $request->user_id;
                    $userquiz->parent_id = $quiz->id;
                    $userquiz->difficulty_level_id = $quiz->difficulty_level_id;
                    $userquiz->quiz_type_id = $quiz->quiz_type_id;
                    $userquiz->quiz_speed_id = $quiz->quiz_speed_id;
                    $userquiz->save();
                   
            }
                $quiz = $userquiz;
            }

            $alreadysave = Performance::where('attempt_id', $request->quiz_id)->get('question_id');

            if (empty($alreadysave)) {

                $data = SaveQuizRoomResult::dispatchNow($request->all());
            }
            else{
                $data = 'success'; 
            }

            if ($data == 'error') {
                return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
            }
            if ($data == 'success') {

                $data['quiz_id'] = $request->quiz_id;
                $data['xp'] = $quiz->xp;
                $data['per'] = $quiz->result;

                return response()->json(['status' => 200, 'message' => 'Result saved succesfully', 'data' => $data]);
            }
        } else {
            return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
        }
    }

    public function reject_invitation(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'room_link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }


        $attempt = Attempt::where('link', $req->room_link)->first();
        if (empty($attempt)) {
            return response()->json(['status' => 204, 'data' => [], 'message' => 'Sorry! Link has been expired. or not found']);
        }
        $challenge = Challange::where('attempt_id', $attempt->id)->where('to_user_id', $req->user_id)->latest()->first();

        if (empty($challenge)) {
            return response()->json(['status' => 201, 'data' => [], 'message' => 'Sorry! No invitation']);
        } else {
            $challenge->deleted_at = date('Y-m-d h:i:s');
            $challenge->save();


            $data = [
                'title' => 'Duel Invitation rejected.',
                'token' => $challenge->from_user->token,
                'link' => $attempt->link,
                'type' => 'dual',
                'message' => User::where('id', $req->user_id)->first()->name . " has been rejected the request",
            ];
            sendNotification($data);

            return response()->json(['status' => 200, 'data' => [], 'message' => 'Rejected succesfully']);
        }
    }
}
