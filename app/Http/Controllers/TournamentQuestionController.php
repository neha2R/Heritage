<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TournamentQuestionImport;
use App\Tournament;
use App\TournamentQuestions;
use App\TournamentQuizeQuestion;
use Illuminate\Support\Facades\Validator;
use App\Question;

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
       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       }  
       $tournament = Tournament::find($request->tournament_id);
       if (empty($tournament)) {
           return response()->json(['status' => 204, 'message' => 'Tournament expired or not found', 'data' => '']);
       }

       $tourQuestions = TournamentQuizeQuestion::where('tournament_id',$request->tournament_id)->first();
       
       if (empty($tourQuestions)) {
           return response()->json(['status' => 204, 'message' => 'No rules found for the quiz', 'data' => '']);
       } else {
        $num=20;
        $questions_ids = json_decode($tourQuestions->questions_id);
        $ques = array_rand($questions_ids, $num );
        $questions = Question::select('id', 'question', 'question_media', 'option1', 'option1_media', 'option2', 'option2_media', 'option3', 'option3_media', 'option4', 'option4_media', 'why_right', 'right_option', 'hint', 'question_media_type')->whereIn('id', $questions_ids)->get();
           return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $questions]);
       }

    }
}
