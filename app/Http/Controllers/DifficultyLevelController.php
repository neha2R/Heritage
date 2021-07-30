<?php

namespace App\Http\Controllers;

use App\DifficultyLevel;
use Illuminate\Http\Request;

class DifficultyLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $difficultyLevels = DifficultyLevel::OrderBy('id', 'DESC')->get();
        return view('level.list', compact('difficultyLevels'));
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
            'name' => 'required|unique:difficulty_levels',
            'weitage_per_question' => 'required|numeric|min:1|max:99',
            'time_per_question' => 'required|numeric|',
        ]);

        $data = new DifficultyLevel;
        $data->name = $request->name;
        $data->weitage_per_question = $request->weitage_per_question;
        $data->time_per_question = $request->time_per_question;
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Level saved Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DifficultyLevel  $difficultyLevel
     * @return \Illuminate\Http\Response
     */
    public function show($difficultyLevel)
    {
        $difficultyLevel = DifficultyLevel::find($difficultyLevel);
        if ($difficultyLevel->status == '1') {
            $difficultyLevel->status = '0';
        } else {
            $difficultyLevel->status = '1';

        }
        $difficultyLevel->save();

        if ($difficultyLevel->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DifficultyLevel  $difficultyLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(DifficultyLevel $difficultyLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DifficultyLevel  $difficultyLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $difficultyLevel)
    {
        $data = DifficultyLevel::find($difficultyLevel);

        $validatedData = $request->validate([
            'name' => 'required|unique:difficulty_levels,name,' . $data->id,
            'weitage_per_question' => 'required|numeric|min:1|max:99',
            'time_per_question' => 'required|numeric|',
        ]);
        $data->name = $request->name;
        $data->weitage_per_question = $request->weitage_per_question;
        $data->time_per_question = $request->time_per_question;
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Level updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DifficultyLevel  $difficultyLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy($difficultyLevel)
    {
        $difficultyLevel = DifficultyLevel::find($difficultyLevel);
        $difficultyLevel->delete();
        if ($difficultyLevel->id) {
            return redirect()->back()->with(['success' => 'Diffulcity Level Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }
}
