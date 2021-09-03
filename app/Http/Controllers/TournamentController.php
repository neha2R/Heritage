<?php

namespace App\Http\Controllers;

use App\Tournament;
use App\QuestionsSetting;
use Illuminate\Http\Request;
use App\AgeGroup;
use App\DifficultyLevel;
use App\Theme;
use App\Domain;
use App\SubDomain;
use App\TournamentQuizeQuestion;
use Storage;
use App\Imports\TournamentQuestionImport;
use Maatwebsite\Excel\Facades\Excel;

//use App\Frequency;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tournaments = Tournament::OrderBy('id', 'DESC')->get();
        $age_groups = AgeGroup::OrderBy('id','DESc')->get();
        $difficulty_levels = DifficultyLevel::OrderBy('id','DESC')->get();
        
        $themes = Theme::OrderBy('id','DESC')->get();
        $domains = Domain::OrderBy('id','DESC')->get();
        $subDomains = SubDomain::OrderBy('id','DESC')->get();
       // $frequencies = Frequency::OrderBy('id','DESC');
        
        return view('tournament.list', compact('tournaments','age_groups','difficulty_levels','themes','domains','subDomains'));
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
            'title' => 'required|regex:/^[a-zA-Z]+$/u|max:255',
            'subdomain_name' => 'required|integer',
            'quize_type' => 'required|integer',
            'age_group_id' => 'required|integer',
            'difficulty_level_id' => 'required|integer',
            'theme_id' => 'required|integer',
            'domain_id' => 'required|integer',
            'session_per_day' => 'required|integer',
            'no_of_players' => 'required|integer',
            'duration' => 'required|integer',
            'duration' => 'required|integer',
            'media_name' => 'required'
        ]);
       
        

        if($request->quize_type == "0")
        {   
           // dd($request->duration);
            $newTournament = new Tournament;
            $newTournament->title = $request->title;
            $newTournament->type = $request->quize_type;
            $newTournament->age_group_id = $request->age_group_id;
            $newTournament->difficulty_level_id = $request->difficulty_level_id;
            $newTournament->theme_id = $request->theme_id;
            $newTournament->domain_id = $request->domain_id;
            $newTournament->sub_domain_id = '1';//$request->sub_domain_id;
            $newTournament->frequency_id = '1';//$request->frequency_id;
            $newTournament->session_per_day = $request->session_per_day;
            $newTournament->no_players = $request->no_of_players;
            $newTournament->duration = $request->duration;
            $newTournament->start_time = $request->start_time;
            $newTournament->interval_session = $request->interval_session;
            if($request->hasfile('media_name'))
            {

                $media_name = $request->file('media_name')->store('tournament','public');
                $newTournament->media_name = $media_name;
            }
            $newTournament->save();
            
        }
        else
        {
            $newTournament = new Tournament;
            $newTournament->title = $request->title;
            $newTournament->type = $request->quize_type;
            $newTournament->age_group_id = $request->age_group_id;
            $newTournament->frequency_id = '1';//$request->frequency_id;
            $newTournament->session_per_day =$request->session_per_day;
            $newTournament->no_players = $request->no_of_players;
            $newTournament->duration = $request->duration;
            $newTournament->start_time = $request->start_time;
            $newTournament->interval_session = $request->interval_bw_session;
            $newTournament->no_of_question = $request->no_of_question;
            $newTournament->marks_per_question = $request->mark_per_question;
            $newTournament->negative_marking = '1';//$request->negative_marking;
            $newTournament->negative_marking_per_question = $request->negative_mark_per_question;
            if($request->hasfile('media_name'))
            {
                $media_name = $request->file('media_name')->store('tournament','public');
                $newTournament->media_name = $media_name;
            }






           $newTournament->save();


            
            // store excel file question 
            Excel::import(new TournamentQuestionImport($newTournament->id), $request->file('tournament_question_bluck'));
            return back();
            
            //dd($newTournament);
           

        }
        return redirect()->route('tournament_add',['id'=>$newTournament->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        //
    }

    public function tournament_add(Request $req)
    {
          
          $tournament=Tournament::where('id',$req->id)->first();
          $questions=QuestionsSetting::with('domain')->with('question')->where('domain_id',$tournament->domain_id)->get();
          return view('tournament.create_tournament',compact('questions','tournament'));
    }

    public function tournament_question_store(Request $req)
    {
        $newQuizeQuestions = new TournamentQuizeQuestion;
        $newQuizeQuestions->questions_id = json_encode($req->questions_id);
        $newQuizeQuestions->tournament_id  = $req->tournament_id;
        $newQuizeQuestions->total_no_question = count($req->questions_id);
        $newQuizeQuestions->save();

        return redirect()->route('tournament.index');
       // dd(json_encode($req->questions_id));
    }
}
