<?php

namespace App\Http\Controllers;

use App\TournamenetUser;
use Illuminate\Http\Request;
use App\Jobs\SaveTournamentResultJob;
use App\Jobs\XpLpOfTournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\TournamentPerformance;
use App\Question;
use App\League;

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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           'tournament_id' => 'required',
           'session_id' => 'required',
           'answer' => 'required',

       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       }  
            $data = SaveTournamentResultJob::dispatchNow($request->all());
            
            $tournamentUsers = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->orderBy('id','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->get();
           
            if($tournamentUsers->count()==5){
            
                $job = (new XpLpOfTournament($request->all()))->delay(now()->addMinutes(1));
                $this->dispatch($job);


               
            // XpLpOfTournament::dispatch($request->all());
            }
            
            if ($data == 'error') {
                return response()->json(['status' => 202, 'message' => 'Something went wrong', 'data' => '']);
            }
            if ($data['status'] == 'success') {
                $response = [];
                $response['user_id'] = $request->user_id;
                if($data['per']==null){
                    $data['per']=0;
                }
             
                $response['percentage'] = $data['per'];
                return response()->json(['status' => 200, 'message' => 'Result saved succesfully', 'data' => $response]);
            }
    } 


       /**
     * Get Rank For APi.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function get_tournament_rank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           'tournament_id' => 'required',
           'session_id' => 'required',

       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       }  
       $response=[];
       $singleuser = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->first();

       if(empty($singleuser)){
        return response()->json(['status' => 204, 'message' => 'No tournament found', 'data' =>$response,]);
       }

       if($singleuser->rank==null){
        return response()->json(['status' => 200, 'message' => 'Rank will be not calculated yet', 'data' => '','result'=>'0']);
       } else{
           $user=[];
           
           $user['user_id'] = $singleuser->user_id;
           $user['rank'] = $singleuser->rank;
           $user['lp'] = $singleuser->lp;
           $user['percentage'] = $singleuser->percentage;

           $tournamentUsers = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->orderBy('rank','ASC')->where('status','completed')->whereDate('created_at', Carbon::today())->get();

           foreach($tournamentUsers as $users){
               $data['rank'] = $users->rank;
               $data['user_id'] = $users->user_id;
               $data['lp'] = $users->lp;
               $data['percentage'] = $users->percentage;
               $response[] = $data;
           }


           return response()->json(['status' => 200, 'message' => 'Rank calculated ','user_data'=>$user, 'data' =>$response,'result'=>'1']);
       }

    }



      /**
     * Get Answer Key of TOurnament For APi.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function get_tournament_answer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           'tournament_id' => 'required',
           'session_id' => 'required',

       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       } 

       $data = [];

       $singleuser = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->first();

       if(empty($singleuser)){
        return response()->json(['status' => 204, 'message' => 'No record found', 'data' => $data,]);
       }

       
            $questions = TournamentPerformance::where('tournamenet_users_id', $singleuser->id)->get();
     
           
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

       


    }



     /**
     * Get user league on tournamnet page.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function userleague(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',

       ]);

       $response=[];

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
       } 

       $leagues = League::select('id','title')->get()->toArray();
       $user['title']='Debler';
       $user['id']=1;

       for($i=0; $i<=28; $i++){
       $rank[] = rand(1,50);
       }
       $response['user'] = $user;
       $response['league'] = $leagues;
       $response['rank'] = $rank;

       return response()->json(['status' => 200, 'data' => $response, 'message' => 'Success']);

    }



     /**
     * Get user league and other league with top 5 players on tournamnet page.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function leaguerank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',

       ]);

       $response=[];

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
       } 

       $leagues = League::select('id','title')->get();
       $top=[];
       $middle=[];
       $bottom=[];

       $your_leage['league_id'] =4;
       $your_leage['league_name'] ='Initiate';

       for($i=1; $i<=5; $i++){
       $top1['rank'] = $i;
       $top1['percentage'] = rand(10,70);
       $top1['user_id'] = $i;
       $top[]= $top1;
       }

       for($i=1; $i<=5; $i++){
        $middel1['rank'] = $i;
        $middel1['percentage'] = rand(10,70);
        $middel1['user_id'] = $i;
        $middle[]= $middel1;
        }

        for($i=1; $i<=5; $i++){
            $bottom1['rank'] = $i;
            $bottom1['percentage'] = rand(10,70);
            $bottom1['user_id'] = $i;
            $bottom[]= $bottom1;
            }
            $leaguedata=[];


            foreach($leagues as $league){
                $alldatas=[];
                $myname = 1;
             if($your_leage['league_id'] != $league->id){   
            for($i=1; $i<=5; $i++){

                $alldatas1['rank'] = $i;
                $alldatas1['percentage'] = rand(10,70);
                $alldatas1['user_id'] = $i;
                $alldatas[]= $alldatas1;
                } 
    $response['oleague'.$league->id]['league_id'] =$league->id;
    $response['oleague'.$league->id]['league_name'] =$league->title;
    $response['oleague'.$myname]['data'] =$alldatas;
    $myname++;
            }

        //  $leaguedata[$league->title] = $bottom;
            }

         $your_leage['top'] =$top;
         $your_leage['middle'] =$middle;
         $your_leage['bottom'] =$bottom;

        $response['your_leage'] = $your_leage;

    //    $response['user'] = $user;
    //    $response['league'] = $leaguedata;
    //    $response['rank'] = $rank;

       return response()->json(['status' => 200, 'data' => $response, 'message' => 'Success']);

    }



    /**
     * Get user xprewards and other xprewards with tournamnet page.
     *
     * @param  \App\TournamenetUser  $tournamenetUser
     * @return \Illuminate\Http\Response
     */
    public function xprewards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',

       ]);

       $response=[];

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
       } 

       $leagues = League::select('id','title','xp')->get();
       
       $your_leage = [];
       $other_league=[];
       $your_leage['user_id'] =2;
       $your_leage['league_id'] =4;
       $your_leage['league'] ='Initiate';
       $your_leage['xp'] =400;

       foreach($leagues as $league){
        $myname=1;
           if($your_leage['league_id'] != $league->id){
        $data['league_id'] =$league->id;
        $data['league'] =$league->title;
        $data['xp'] =   $league->xp;
        $response['oleague'.$myname] = $data;
        $myname++;
           }
       }

    //    $response['user'] = $user;
    //    $response['other_league'] = $other_league;
       $response['your_leage'] = $your_leage;
    //    $response['rank'] = $rank;

       return response()->json(['status' => 200, 'data' => $response, 'message' => 'Success']);

    }


    
}
