<?php

namespace App\Http\Controllers;

use App\QuizType;
use Illuminate\Http\Request;

class QuizTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizTypes = QuizType::OrderBy('id', 'DESC')->get();
        return view('quiz_type.list', compact('quizTypes'));
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
            'name' => 'required|unique:quiz_types',
            'no_of_player' => 'required|numeric|min:1',
        ]);

        $data = new QuizType;
        $data->name = $request->name;
        $data->no_of_player = $request->no_of_player;
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect('admin/quiztype')->with(['success' => 'Quiz Type saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuizType  $quizType
     * @return \Illuminate\Http\Response
     */
    public function show($quizType)
    {
        $quizType = QuizType::find($quizType);
        if ($quizType->status == '1') {
            $quizType->status = '0';
        } else {
            $quizType->status = '1';

        }
        $quizType->save();

        if ($quizType->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuizType  $quizType
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizType $quizType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuizType  $quizType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quizType)
    {
        $data = QuizType::find($quizType);

        $validatedData = $request->validate([
            'name' => 'required|unique:quiz_types,name,' . $data->id,
            'no_of_player' => 'required|numeric|min:1',

        ]);

        $data->name = $request->name;
        $data->no_of_player = $request->no_of_player;
        $data->save();
        if ($data->id) {
            return redirect()->back()->with(['success' => 'Quiz Type updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuizType  $quizType
     * @return \Illuminate\Http\Response
     */
    public function destroy($quizType)
    {
        $quizType = QuizType::find($quizType);
        $quizType->delete();
        if ($quizType->id) {
            return redirect()->back()->with(['success' => 'Quiz Type Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }
}
