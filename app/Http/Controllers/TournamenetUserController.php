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

       $singleuser = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->first();

       if(empty($singleuser)){
        return response()->json(['status' => 204, 'message' => 'No tournament found', 'data' => '',]);
       }

       if($singleuser->rank==null){
        return response()->json(['status' => 200, 'message' => 'Rank will be not calculated yet', 'data' => '','result'=>'0']);
       } else{
           $user=[];
           $response=[];
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

       $singleuser = TournamenetUser::where('tournament_id',$request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->first();

       if(empty($singleuser)){
        return response()->json(['status' => 204, 'message' => 'No record found', 'data' => '',]);
       }

       
            $questions = TournamentPerformance::where('tournamenet_users_id', $singleuser->id)->get();
     
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

       


    }




    
}
