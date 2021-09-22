<?php

namespace App\Http\Controllers;

use App\TournamenetUser;
use Illuminate\Http\Request;
use App\Jobs\SaveTournamentResultJob;
use App\Jobs\XpLpOfTournament;
use Carbon\Carbon;

class TournamenetUserController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function show(TournamenetUser $tournamenetUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function edit(TournamenetUser $tournamenetUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TournamenetUser $tournamenetUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(TournamenetUser $tournamenetUser)
    {
        //
    }


       /**
     * Store TOurnament Result From APi.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function tournament_result(Request $request)
    {
    
            $data = SaveTournamentResultJob::dispatchNow($request->all());
            
            $tournamentUsers = TournamenetUser::where('tournament_id',$result->tournament_id)->where('session_id', $result->session_id)->orderBy('id','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->get();
            
            if($tournamentUsers->count()==3){
            XpLpOfTournament::dispatch($request)
                    ->delay(now()->addMinutes(1));
            }
            if ($data == 'error') {
                return response()->json(['status' => 202, 'message' => 'Something went wrong', 'data' => '']);
            }
            if ($data == 'success') {
                $data = [];
                $data['user_id'] = $request->user_id;
                return response()->json(['status' => 200, 'message' => 'Result saved succesfully', 'data' => $data]);
            }
    } 


    
    
}
