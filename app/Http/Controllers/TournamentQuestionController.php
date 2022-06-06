<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TournamentQuestionImport;
use App\Tournament;
use App\TournamentQuestions;
use App\TournamentQuizeQuestion;
use Illuminate\Support\Facades\Validator;
use App\Question;
use App\TournamentSessionQuestion;

class TournamentQuestionController extends Controller
{
    //

    public function import(Request $request)
    {
        Excel::import(new QuestionImport, $request->file('bulk'));
        return back();

    }

    public function tournament_questions(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'tournament_id' => 'required',
           'session_id' => 'required',

       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       }  
       $tournament = Tournament::find($request->tournament_id);
       if (empty($tournament)) {
           return response()->json(['status' => 204, 'message' => 'Tournament expired or not found', 'data' => '']);
       }

    //    $sessions = SessionsPerDay::where('tournament_id',$request->tournament_id)->where('id',$request->session_id)->first();
    //    if ($sessions->)) {
    //        return response()->json(['status' => 204, 'message' => 'Tournament expired or not found', 'data' => '']);
    //    }

       
       $tourQuestions = TournamentSessionQuestion::where('session_id',$request->session_id)->where('tournament_id',$request->tournament_id)->first();
       
       if (empty($tourQuestions)) {
           return response()->json(['status' => 204, 'message' => 'Question not created yet make sure one member has to be join the tournament', 'data' => '']);
       } else {
           $mydata=[];
        $questions_ids = json_decode($tourQuestions->questions);
                foreach($questions_ids as $ids){
                    $questions = Question::select('id', 'question', 'question_media', 'option1', 'option1_media', 'option2', 'option2_media', 'option3', 'option3_media', 'option4', 'option4_media', 'why_right', 'right_option', 'hint', 'question_media_type','type', 'ques_type')->where('id', $ids)->first()->toArray(); 
                    $mydta[] = $questions; 
                }
                $data['question'] = $mydta;
                $data['whole_quiz_time'] = '1';
                $data['time'] = $tournament->duration*60;
                $data['total_question'] = count($questions_ids);
                $data['total_question_in_quiz'] = count($questions_ids);
              
    return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
    
       }

    }
}
