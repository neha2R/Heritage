<?php

namespace App\Http\Controllers;

use App\Attempt;
use App\Jobs\SaveResult;
use App\Performance;
use App\Question;
use App\QuizDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'quiz_type_id' => 'required',
            'difficulty_level_id' => 'required',
            'quiz_speed_id' => 'required',
            'domains' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $data = new Attempt;
        $data->user_id = $request->user_id;
        $data->quiz_type_id = $request->quiz_type_id;
        $data->difficulty_level_id = $request->difficulty_level_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->save();
        $domain = new QuizDomain;
        $domain->attempts_id = $data->id;
        $domain->domain_id = $request->domains;
        $domain->save();
        // $rule = QuizRule::where('quiz_type_id', $request->quiz_type_id)->orWhere('quiz_speed_id', $request->quiz_speed_id)->first();
        // $data = $data->toArray();
        // if (!empty($rule)) {
        //     $data['rule'] = $rule->toArray();
        // }
        return response()->json(['status' => 200, 'message' => 'Quiz created successfully', 'data' => $data]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function show(Attempt $attempt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function edit(Attempt $attempt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attempt $attempt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attempt $attempt)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function saveresult(Request $request)
    {
        $quiz = Attempt::find($request->quiz_id);
        if (!empty($quiz)) {
            $data = SaveResult::dispatchNow($request->all());
            if ($data == 'error') {
                return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
            }
            if ($data == 'success') {
                $data = [];
                $data['quiz_id'] = $request->quiz_id;
                return response()->json(['status' => 200, 'message' => 'Result saved succesfully', 'data' => $data]);
            }
        } else {
            return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
        }

    }

    public function get_result(Request $request)
    {
        $quiz = Attempt::find($request->quiz_id);
        if (!empty($quiz)) {
            $questions_id = Performance::where('attempt_id', $request->quiz_id)->where('result', 1)->get('question_id');
            if (empty($questions_id)) {
                return response()->json(['status' => 202, 'message' => 'Your quiz is not submitted yet', 'data' => '']);
            }
            $questions = Question::whereIn('id', $questions_id)->get();

            return response()->json(['status' => 200, 'message' => 'Result succes', 'result' => '75']);
            // foreach ($questions as $question) {

            // }

        } else {
            return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
        }

    }

    public function get_answerkey(Request $request)
    {
        $quiz = Attempt::find($request->quiz_id);
        if (!empty($quiz)) {
            $questions = Performance::where('attempt_id', $request->quiz_id)->get();
            $data = [];
            foreach ($questions as $question) {
                $res = [];
                $que = Question::where('id', $question->question_id)->first();
                $res['question'] = $que->question;
                if ($que->right_option == 1) {
                    $res['right_option'] = $que->option1;
                } elseif ($que->right_option == 2) {
                    $res['right_option'] = $que->option2;
                } elseif ($que->right_option == 3) {
                    $res['right_option'] = $que->option3;
                } elseif ($que->right_option == 4) {
                    $res['right_option'] = $que->option4;
                } else {
                    $res['right_option'] = '';

                }
                if ($question->selected_option == 1) {
                    $res['your_option'] = $que->option1;
                } elseif ($question->selected_option == 2) {
                    $res['your_option'] = $que->option2;
                } elseif ($question->selected_option == 3) {
                    $res['your_option'] = $que->option3;
                } elseif ($question->selected_option == 4) {
                    $res['your_option'] = $que->option4;
                } elseif ($question->selected_option == 0) {
                    $res['your_option'] = 'not attempt';
                } else {
                    $res['your_option'] = '';

                }
                $res['question_id'] = $que->id;
                $data[] = $res;

            }
            return response()->json(['status' => 200, 'message' => 'Result show', 'data' => $data]);

        } else {
            return response()->json(['status' => 202, 'message' => 'Quiz not found', 'data' => '']);
        }

    }

}
