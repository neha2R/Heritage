<?php

namespace App\Http\Controllers;

use App\QuizRule;
use App\QuizSpeed;
use App\QuizType;
use Illuminate\Http\Request;

class QuizRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizType = QuizType::OrderBy('id', 'DESC')->get();
        $quizSpeed = QuizSpeed::OrderBy('id', 'DESC')->get();
        $quizRules = QuizRule::all();
        return view('quiz_rules.list', compact('quizType', 'quizSpeed', 'quizRules'));
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
        $validatedData = $request->validate([
            'quiz_speed_id' => 'required',
            'quiz_type_id' => 'required',
            'scoring' => 'required',
            'negative_marking' => 'required',
            'time_limit' => 'required',
            'no_of_players' => 'required',
            'hint_guide' => 'required',
            'que_navigation' => 'required',
            // 'more' => 'required',
        ]);

        $data = new QuizRule;
        $data->quiz_type_id = $request->quiz_type_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->scoring = $request->scoring;
        $data->negative_marking = $request->negative_marking;
        $data->time_limit = $request->time_limit;
        $data->no_of_players = $request->no_of_players;
        $data->hint_guide = $request->hint_guide;
        $data->que_navigation = $request->que_navigation;
        $data->more = json_encode($request->more);
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Quiz Rule saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quiz_rule  $quiz_rule
     * @return \Illuminate\Http\Response
     */
    public function show(QuizRule $quizrule)
    {
        if ($quizrule->status == '1') {
            $quizrule->status = '0';
        } else {
            $quizrule->status = '1';

        }
        $quizrule->save();

        if ($quizrule->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quiz_rule  $quiz_rule
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizRule $quizrule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quiz_rule  $quiz_rule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quizrule)
    {
        $data = QuizRule::find($quizrule);
        $validatedData = $request->validate([
            'quiz_speed_id' => 'required',
            'quiz_type_id' => 'required',
            'scoring' => 'required',
            'negative_marking' => 'required',
            'time_limit' => 'required',
            'no_of_players' => 'required',
            'hint_guide' => 'required',
            'que_navigation' => 'required',
            // 'more' => 'required',
        ]);

        $data->quiz_type_id = $request->quiz_type_id;
        $data->quiz_speed_id = $request->quiz_speed_id;
        $data->scoring = $request->scoring;
        $data->negative_marking = $request->negative_marking;
        $data->time_limit = $request->time_limit;
        $data->no_of_players = $request->no_of_players;
        $data->hint_guide = $request->hint_guide;
        $data->que_navigation = $request->que_navigation;
        $data->more = json_encode($request->more);
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Quiz Rule updated Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quiz_rule  $quiz_rule
     * @return \Illuminate\Http\Response
     */
    public function destroy($quizrule)
    {

        $quizrule = QuizRule::find($quizrule);
        $quizrule->delete();
        if ($quizrule->id) {
            return redirect()->back()->with(['success' => 'Rule Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    public function get_rule_type(Request $req)
    {
        return json_encode(QuizType::where('id', $req->id)->first());
    }

    public function get_rule_speed(Request $req)
    {
        return json_encode(QuizSpeed::where('id', $req->id)->first());
    }

    public function quiz_rules(Request $request)
    {
        $quiz_rules = QuizRule::where('quiz_type_id', $request->quiz_type_id)->where('quiz_speed_id', $request->quiz_speed_id)->first();
        
        if (empty($quiz_rules)) {
            return response()->json(['status' => 204, 'message' => 'No rules found for the quiz', 'data' => '']);
        } else {
            // $data = json_decode($quiz_rules->more);
            $quiz_rules->more = json_decode($quiz_rules->more);
            $data = $quiz_rules->toArray();
            return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
        }
    }

}
