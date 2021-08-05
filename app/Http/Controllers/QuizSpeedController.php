<?php

namespace App\Http\Controllers;

use App\QuizSpeed;
use Illuminate\Http\Request;

class QuizSpeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizSpeeds = QuizSpeed::OrderBy('id', 'DESC')->get();
        return view('speed.list', compact('quizSpeeds'));
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
            'name' => 'required|unique:quiz_speeds',
            'duration' => 'required|numeric|min:1',
            'no_of_question' => 'required|numeric|min:1',
        ]);

        $data = new QuizSpeed;
        $data->name = $request->name;
        $data->duration = $request->duration;
        $data->no_of_question = $request->no_of_question;
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect('admin/quizspeed')->with(['success' => 'Quiz Speed saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuizSpeed  $quizSpeed
     * @return \Illuminate\Http\Response
     */
    public function show($quizSpeed)
    {
        $quizSpeed = QuizSpeed::find($quizSpeed);
        if ($quizSpeed->status == '1') {
            $quizSpeed->status = '0';
        } else {
            $quizSpeed->status = '1';

        }
        $quizSpeed->save();

        if ($quizSpeed->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuizSpeed  $quizSpeed
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizSpeed $quizSpeed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuizSpeed  $quizSpeed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quizSpeed)
    {
        $data = QuizSpeed::find($quizSpeed);

        $validatedData = $request->validate([
            'name' => 'required|unique:quiz_speeds,name,' . $data->id,
            'duration' => 'required|numeric|min:1',
            'no_of_question' => 'required|numeric|min:1',
        ]);

        $data->name = $request->name;
        $data->duration = $request->duration;
        $data->no_of_question = $request->no_of_question;
        $data->save();
        if ($data->id) {
            return redirect()->back()->with(['success' => 'Quiz Speed updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuizSpeed  $quizSpeed
     * @return \Illuminate\Http\Response
     */
    public function destroy($quizSpeed)
    {
        $quizSpeed = QuizSpeed::find($quizSpeed);
        $quizSpeed->delete();
        if ($quizSpeed->id) {
            return redirect()->back()->with(['success' => 'Quiz Speed Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }
}
