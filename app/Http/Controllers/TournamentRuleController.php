<?php

namespace App\Http\Controllers;

use App\TournamentRule;
use Illuminate\Http\Request;

class TournamentRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $quizRules = TournamentRule::all();
        return view('quiz_rules.list', compact('quizType'));

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
            'tournament_id' => 'required',
            'details' => 'required',
            // 'more' => 'required',
        ]);

        $data = new TournamentRule;
        $data->tournament_id = $request->tournament_id;
        $data->details = json_encode($request->details);
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Tournament Rule saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TournamentRule  $tournamentRule
     * @return \Illuminate\Http\Response
     */
    public function show(TournamentRule $tournamentRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TournamentRule  $tournamentRule
     * @return \Illuminate\Http\Response
     */
    public function edit(TournamentRule $tournamentRule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TournamentRule  $tournamentRule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TournamentRule $tournamentRule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TournamentRule  $tournamentRule
     * @return \Illuminate\Http\Response
     */
    public function destroy(TournamentRule $tournamentRule)
    {
        //
    }
}
