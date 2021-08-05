<?php

namespace App\Http\Controllers;

use App\AgeGroup;
use App\DifficultyLevel;
use App\Domain;
use App\Question;
use App\QuestionsSetting;
use App\Subdomain;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::OrderBy('id', 'DESC')->get();
        $age_groups = AgeGroup::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $subdomains = Subdomain::OrderBy('id', 'DESC')->get();
        $diffulcitylevels = DifficultyLevel::OrderBy('id', 'DESC')->get();

        return view('question.list', compact('questions', 'age_groups', 'domains', 'diffulcitylevels', 'subdomains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $age_groups = AgeGroup::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $sub_domains = Sundomain::OrderBy('id', 'DESC')->get();
        $diffulcitylevels = DifficultyLevel::OrderBy('id', 'DESC')->get();

        return view('question.add', compact('age_groups', 'domains'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'right_option' => 'required',
            'domain_id' => 'required',
            'subdomain_id' => 'required',
            'age_group_id' => 'required',
            'diffulcity_level_id' => 'required',
        ]);
        $option1_media = '';
        $option2_media = '';
        $option3_media = '';
        $option4_media = '';
        $question_media = '';
        if ($request->has('question_media')) {
            $foldername = 'question';
            $question_media = $request->file('question_media')->store($foldername, 'public');
        }
        if ($request->has('option1_media')) {
            $foldername = 'option1';
            $option1_media = $request->file('option1_media')->store($foldername, 'public');
        }
        if ($request->has('option2_media')) {
            $foldername = 'option2';
            $option2_media = $request->file('option2_media')->store($foldername, 'public');
        }
        if ($request->has('option3_media')) {
            $foldername = 'option3';
            $option3_media = $request->file('option3_media')->store($foldername, 'public');
        }
        if ($request->has('option4_media')) {
            $foldername = 'option4';
            $option4_media = $request->file('option4_media')->store($foldername, 'public');
        }

        $data = new Question;
        $data->question = $request->question;
        $data->option1 = $request->option1;
        $data->option2 = $request->option2;
        $data->option3 = $request->option3;
        $data->option4 = $request->option4;
        $data->question_media = $question_media;
        $data->option1_media = $option1_media;
        $data->option2_media = $option2_media;
        $data->option3_media = $option3_media;
        $data->option4_media = $option4_media;
        $data->right_option = $request->right_option;
        $data->save();

        $quessetting = new QuestionsSetting;
        $quessetting->question_id = $data->id;
        $quessetting->age_group_id = $request->age_group_id;
        $quessetting->difficulty_level_id = $request->diffulcity_level_id;
        $quessetting->domain_id = $request->domain_id;
        $quessetting->subdomain_id = $request->subdomain_id;
        $quessetting->save();
        if ($data->id) {
            return redirect()->back()->with(['success' => 'Question saved successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
