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
use App\Tournament;
use App\UserLeagueWithPer;
use App\User;
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

        $tournamentUsers = TournamenetUser::where('tournament_id', $request->tournament_id)->where('session_id', $request->session_id)->orderBy('id', 'DESC')->where('status', 'completed')->whereDate('created_at', Carbon::today())->get();

        if ($tournamentUsers->count() == 1) {

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
            if ($data['per'] == null) {
                $data['per'] = 0;
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
        $response = [];
        $singleuser = TournamenetUser::where('tournament_id', $request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks', 'DESC')->where('status', 'completed')->whereDate('created_at', Carbon::today())->first();

        if (empty($singleuser)) {
            return response()->json(['status' => 204, 'message' => 'No tournament found', 'data' => $response,]);
        }

        if ($singleuser->rank == null) {
            // $job = (new XpLpOfTournament($request->all()))->delay(now()->addMinutes(1));
            // $this->dispatch($job);
            return response()->json(['status' => 200, 'message' => 'Rank will be not calculated yet', 'data' => '', 'result' => '0']);
        } else {
            $user = [];

            $user['user_id'] = $singleuser->user_id;
            $user['rank'] = $singleuser->rank;
            $user['lp'] = $singleuser->lp;
            $user['percentage'] = $singleuser->percentage;

            $tournamentUsers = TournamenetUser::where('tournament_id', $request->tournament_id)->where('session_id', $request->session_id)->orderBy('rank', 'ASC')->where('status', 'completed')->whereDate('created_at', Carbon::today())->get();

            foreach ($tournamentUsers as $users) {
                $data['rank'] = $users->rank;
                $data['user_id'] = $users->user_id;
                $data['lp'] = $users->lp;
                $data['percentage'] = $users->percentage;
                $response[] = $data;
            }


            return response()->json(['status' => 200, 'message' => 'Rank calculated ', 'user_data' => $user, 'data' => $response, 'result' => '1']);
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

        $singleuser = TournamenetUser::where('tournament_id', $request->tournament_id)->where('session_id', $request->session_id)->where('user_id', $request->user_id)->orderBy('marks', 'DESC')->where('status', 'completed')->whereDate('created_at', Carbon::today())->first();

        if (empty($singleuser)) {
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

        $response = [];

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
        }
        $group = age_group_by_user($request->user_id);
        //   $tournaments = Tournament::select('id')->where('age_group_id',$group->id)->whereMonth('end_time', '>', date('m'))->get()->toArray();

        $daily = Tournament::where('age_group_id', $group->id)->where('frequency_id', 1)->count();

        $weekly = Tournament::where('age_group_id', $group->id)->where('frequency_id', 2)->count();

        $month = Tournament::where('age_group_id', $group->id)->where('frequency_id', 3)->count();

        $week = Carbon::now()->weekOfMonth;
        $day =  Carbon::now()->day;
        $totaltour = ($daily * $day) + ($weekly * $week) + $month;
        $totallp = $totaltour * $totaltour;

        //   dd($daily,$weekly,$month); 
        // get all user with comulative lp (sum of lp)
        //    $userTours = TournamenetUser::whereIn('tournament_id',$tournaments)->selectRaw("SUM(lp) as cu_lp,user_id")->groupBy('user_id')->whereMonth('created_at', date('m'))->pluck('cu_lp','user_id')->toArray();

        $userTours = TournamenetUser::selectRaw("SUM(lp) as cu_lp")->where('user_id', $request->user_id)->whereMonth('created_at', Carbon::now()->month)->first();
        //    arsort($userTours);
        $count1 = $userTours->cu_lp / $totallp;
        $count2 = $count1 * 100;
        $percentage = number_format($count2, 0);
        if ($percentage >= 0 && $percentage <= 30) {

            $user['title'] = 'Initiate';
            $user['id'] = 5;
        }
        if ($percentage >= 31 && $percentage <= 50) {
            $user['title'] = 'Dabbler';
            $user['id'] = 4;
        }
        if ($percentage >= 51 && $percentage <= 70) {
            $user['title'] = 'Scholar';
            $user['id'] = 3;
        }
        if ($percentage >= 71 && $percentage <= 90) {
            $user['title'] = 'Culture Vulture';
            $user['id'] = 2;
        }
        if ($percentage >= 91 && $percentage <= 100) {
            $user['title'] = 'Expert';
            $user['id'] = 1;
        }
        //   dd($league);



        $leagues = League::select('id', 'title')->get()->toArray();

        //    $user['title']='Debler';
        //    $user['id']=1;

        //    for($i=0; $i<=28; $i++){
        //    $rank[] = rand(1,50);
        //    }
        $rank = TournamenetUser::where('user_id', $request->user_id)->pluck('rank')->toArray();
        $rank = array_map(function ($rank) {
            return (is_null($rank)) ? 0 : $rank;
        }, $rank);
        $response['user'] = $user;
        $response['league'] = $leagues;
        $response['rank'] = $rank;
        $response['percentage'] = $percentage;
        $userleague = UserLeagueWithPer::where('user_id', $request->user_id)->first();

        if ($userleague) {
            $userleague->league_id = $user['id'];
            $userleague->percentage = $percentage;
            $userleague->save();
        } else {

            $userleague = new UserLeagueWithPer;
            $userleague->user_id = $request->user_id;
            $userleague->league_id = $user['id'];
            $userleague->percentage = $percentage;
            $userleague->save();
        }
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

        $response = [];

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
        }
        $userleague = UserLeagueWithPer::where('user_id', $request->user_id)->first();
        $leagues = League::select('id', 'title')->get();
        $top = [];
        $middle = [];
        $bottom = [];

        if (empty($userleague)) {
            $your_leage['league_id'] = 5;
            $your_leage['league_name'] = 'Initiate';
            $alluserleague = [];
        } else {
            $your_leage['league_id'] = $userleague->league_id;
            $your_leage['league_name'] = League::find($userleague->league_id)->title;
            $alluserleague = UserLeagueWithPer::where('league_id', $userleague->league_id)->orderBy('percentage', 'DESC')->get();
        }


        if (empty($alluserleague)) {
            $user_league = [];
        } else {
            $rank = 1;
            foreach ($alluserleague as $alluser) {
                $user_league1['rank'] = $rank;
                $user_league1['percentage'] = $alluser->percentage;
                $user_league1['user_id'] = $alluser->user_id;
                $user_league[] = $user_league1;
                $rank++;
                if ($alluser->user_id == $request->user_id) {
                    break;
                }
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            $top1['rank'] = $i;
            $top1['percentage'] = rand(10, 70);
            $top1['user_id'] = $i;
            $top[] = $top1;
        }

        for ($i = 1; $i <= 5; $i++) {
            $middel1['rank'] = $i;
            $middel1['percentage'] = rand(10, 70);
            $middel1['user_id'] = $i;
            $middle[] = $middel1;
        }

        for ($i = 1; $i <= 5; $i++) {
            $bottom1['rank'] = $i;
            $bottom1['percentage'] = rand(10, 70);
            $bottom1['user_id'] = $i;
            $bottom[] = $bottom1;
        }
        $leaguedata = [];

        $myname = 1;
        foreach ($leagues as $league) {
            $alldatas = [];

            if ($your_leage['league_id'] != $league->id) {

                $leagueWiseData = UserLeagueWithPer::where('league_id', $league->id)->orderBy('percentage', 'DESC')->take(5)->get();
                if (!empty($leagueWiseData)) {
                    $rank = 1;
                    foreach ($leagueWiseData as $leagueWise) {

                        $alldatas1['rank'] = $rank;
                        $alldatas1['percentage'] = $leagueWise->percentage;
                        $alldatas1['user_id'] = $leagueWise->user_id;
                        $alldatas[] = $alldatas1;
                        $rank++;
                    }
                }
                $response['oleague' . $myname]['league_id'] = $league->id;
                $response['oleague' . $myname]['league_name'] = $league->title;
                $response['oleague' . $myname]['data'] = $alldatas;

                $myname++;
            }

            //  $leaguedata[$league->title] = $bottom;
        }

        //  $your_leage['top'] =$top;
        //  $your_leage['middle'] =$middle;
        //  $your_leage['bottom'] =$bottom;
        $your_leage['top'] = $user_league;

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

        $response = [];

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
        }

        $group = age_group_by_user($request->user_id);
        //   $tournaments = Tournament::select('id')->where('age_group_id',$group->id)->whereMonth('end_time', '>', date('m'))->get()->toArray();

        $daily = Tournament::where('age_group_id', $group->id)->where('frequency_id', 1)->count();

        $weekly = Tournament::where('age_group_id', $group->id)->where('frequency_id', 2)->count();

        $month = Tournament::where('age_group_id', $group->id)->where('frequency_id', 3)->count();

        $week = Carbon::now()->weekOfMonth;
        $day =  Carbon::now()->day;
        $totaltour = ($daily * $day) + ($weekly * $week) + $month;
        $totallp = $totaltour * 50; // Max 50 lp can be get by user in single tournament

        $userTours = TournamenetUser::selectRaw("SUM(lp) as cu_lp")->where('user_id', $request->user_id)->whereMonth('created_at', Carbon::now()->month)->first();
        //    arsort($userTours);
        $your_leage = [];
        $other_league = [];

        $count1 = $userTours->cu_lp / $totallp;
        $count2 = $count1 * 100;
        $percentage = number_format($count2, 0);
        if ($percentage >= 0 && $percentage <= 30) {

            $your_leage['league'] = 'Initiate';
            $your_leage['league_id'] = 5;
            $your_leage['xp'] = 400;
        }
        if ($percentage >= 31 && $percentage <= 50) {
            $your_leage['league'] = 'Dabbler';
            $your_leage['league_id'] = 4;
            $your_leage['xp'] = 800;
        }
        if ($percentage >= 51 && $percentage <= 70) {
            $your_leage['league'] = 'Scholar';
            $your_leage['league_id'] = 3;
            $your_leage['xp'] = 1200;
        }
        if ($percentage >= 71 && $percentage <= 90) {
            $your_leage['league'] = 'Culture Vulture';
            $your_leage['league_id'] = 2;
            $your_leage['xp'] = 1600;
        }
        if ($percentage >= 91 && $percentage <= 100) {
            $your_leage['league'] = 'Expert';
            $your_leage['league_id'] = 1;
            $your_leage['xp'] = 2000;
        }

        $leagues = League::select('id', 'title', 'xp')->get();


        $your_leage['user_id'] = $request->user_id;
        //    $your_leage['league_id'] =4;
        //    $your_leage['league'] ='Initiate';
        //    $your_leage['xp'] =400;
        $myname = 1;
        foreach ($leagues as $league) {

            if ($your_leage['league_id'] != $league->id) {
                $data['league_id'] = $league->id;
                $data['league'] = $league->title;
                $data['xp'] =   $league->xp;
                $response['oleague' . $myname] = $data;
                $myname++;
            }
        }

        //    $response['user'] = $user;
        //    $response['other_league'] = $other_league;
        $response['your_leage'] = $your_leage;
        //    $response['rank'] = $rank;

        return response()->json(['status' => 200, 'data' => $response, 'message' => 'Success']);
    }

    public function leaderboardranking(Request $request)
    {
        
        //  dd(date('m',strtotime('jan')));

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',

        ]);

        $response = [];

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => $response, 'message' => $validator->errors()]);
        }
        if (isset($request->month)) {
            $monthname =    date('m', strtotime($request->month));
        } else {
            $monthname = date('m');
        }
        $check = true;
        if ($request->contact_id) {
            $setting =  userProfileSetting($request->user_id);
            if ($setting == 'all') {
                $check = true;
            } else {
                $check = false;
            }
        }

        if (!$check) {
            return response()->json(['status' => 201, 'message' => 'Profile set not visible to all', 'data' => $response]);
        }
        $group = age_group_by_user($request->user_id);
        $daily =0;
        $weekly=0;
        $month = 0;
      
       $noofdays= date('t', strtotime($request->month));
       $year=date('Y');
        for($i =1; $i<= $noofdays; $i++){
       $date = date("$year-$monthname-$i");
            $daily = Tournament::where('age_group_id', $group->id)->where('frequency_id', 1)->count();
       if($i==1)
       {
            // $weekly = Tournament::where('age_group_id', $group->id)->where('frequency_id', 2)->count();
            $month = Tournament::where('age_group_id', $group->id)->where('frequency_id', 3)->count();
       }
       if($i%7==1)
       {
         $weekly = Tournament::where('age_group_id', $group->id)->where('frequency_id', 2)->count();  
       }
       $totallpperday = $daily+ $weekly+ $month*50;
       $users = User::where('type','2')->get();
       
       $totallp=[];
       foreach($users as $user){
              $totallp[$user->id]=  TournamenetUser::where('user_id',$user->id)->whereDate('created_at', $date)->sum('lp');
                arsort($totallp);  // sor array in decending order according to value (for rank)

             
       }
        $monthdata[$i] = $totallp;
     

    }
//    dd($monthdata);
    foreach($monthdata as $key=>$data){
          $rank[]=  array_search($request->user_id, array_keys($data)) ? array_search($request->user_id, array_keys($data))+1 :0; // array index start from zero so +1

    }
   
      
        $response['rank'] = $rank;

        return response()->json(['status' => 200, 'data' => $response, 'message' => 'Success']);
    }
}
