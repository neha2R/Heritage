<?php

namespace App\Http\Controllers;

use App\Tournament;
use App\QuestionsSetting;
use Illuminate\Http\Request;
use App\AgeGroup;
use App\DifficultyLevel;
use App\Theme;
use App\Domain;
use App\Subdomain;
use App\TournamentQuizeQuestion;
use Storage;
use App\Imports\TournamentQuestionImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Frequency;
use Response;
use Carbon\Carbon;
use App\TournamenetUser;
use App\SessionsPerDay;
use App\QuizRule;
use Illuminate\Support\Facades\Validator;
use App\TournamentSessionQuestion;
use App\Jobs\AddSessionQuestionJob;
use App\User;
use App\TournamentRule;

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
      //  dd($tournaments);
        $age_groups = AgeGroup::OrderBy('id','DESc')->get();
        $difficulty_levels = DifficultyLevel::OrderBy('id','DESC')->get();
        
        $themes = Theme::OrderBy('id','DESC')->get();
        $domains = Domain::OrderBy('id','DESC')->get();
        $subDomains = Subdomain::OrderBy('id','DESC')->get();
        $frequencies = Frequency::get();
       
        return view('tournament.list', compact('tournaments','age_groups','difficulty_levels','themes','domains','subDomains','frequencies'));
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
         // dd($request);
        
        
        // add normal quize 
        if($request->quize_type == "0")
        {   
            $validatedData = $request->validate([
                'title' => 'required|',
                'sub_domain_id' => 'required|integer',
                'quize_type' => 'required|integer',
                'age_group_id' => 'required|integer',
                'difficulty_level_id' => 'required|integer',
                'theme_id' => 'required|integer',
                'domain_id' => 'required|integer',
                'no_of_players' => 'required|integer',
                'duration' => 'required|integer',
                'media_name' => 'required',
                'sponsor_media_name'=>'required',
                'no_of_question'=>'required',
    
            ]);
           
            if($request->frequency_id==1){
                $validatedData = $request->validate([
                    'session_per_day' => 'required|integer',
                ]);
                $interval_session = $request->interval_session;
                $session_per_day = $request->session_per_day;
                $is_attempt='0';
            }
             else{
                $validatedData = $request->validate([
                    'is_attempt' => 'required|integer',

                ]);
                $interval_session =   1440;
                $session_per_day = 1;
                $is_attempt=$request->is_attempt;
            }

            $newTournament = new Tournament;
            $newTournament->title = $request->title;
            $newTournament->type = $request->quize_type;
            $newTournament->age_group_id = $request->age_group_id;
            $newTournament->difficulty_level_id = $request->difficulty_level_id;
            $newTournament->theme_id = $request->theme_id;
            $newTournament->domain_id = $request->domain_id;
            $newTournament->sub_domain_id = $request->sub_domain_id;
            $newTournament->frequency_id = $request->frequency_id;
            $newTournament->session_per_day = $session_per_day;
            $newTournament->no_players = $request->no_of_players;
            $newTournament->duration = $request->duration;
            $newTournament->start_time = $request->start_time;
            $newTournament->is_attempt = $is_attempt;
            $newTournament->no_of_question = $request->no_of_question;
            $newTournament->end_time = $request->end_time;

            $newTournament->interval_session = $interval_session;
            if($request->hasfile('media_name'))
            {

                $media_name = $request->file('media_name')->store('tournament','public');
                $newTournament->media_name = $media_name;
            }
            if($request->hasFile('sponsor_media_name'))
            {
                $sponsor_media_name = $request->file('sponsor_media_name')->store('sponsor','public');
                $newTournament->sponsor_media_id = $sponsor_media_name;
            }
            $newTournament->save();
            // save session for first time
            $SessionsPerDay = new SessionsPerDay;
            $starttime = date('H:i',strtotime($request->start_time));
            $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));
            $SessionsPerDay->start_time =$starttime; 
            $SessionsPerDay->end_time = $endtime;
            $SessionsPerDay->duration = $request->duration;
            $SessionsPerDay->tournament_id = $newTournament->id;
            $SessionsPerDay->save();
            $sess = $session_per_day-1;

            if($request->frequency_id==1){
          
             for($sess; $sess>=0; $sess--){

                $starttime = date('H:i',strtotime("+$request->interval_session minutes", strtotime($endtime)));  
                $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));                
                
                $secondSession = new SessionsPerDay;
                $secondSession->start_time =$starttime; 
                $secondSession->end_time = $endtime;
                $secondSession->duration = $request->duration;
                $secondSession->tournament_id = $newTournament->id;
                $secondSession->save();
                
             }
            
            }
            // if($request->frequency_id=='1'){

            //     $starttime = date('H:i',strtotime($request->start_time));
            //     $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));
            //     $secondSession = new SessionsPerDay;
            //     $secondSession->start_time =$starttime; 
            //     $secondSession->end_time = $endtime;
            //     $secondSession->duration = $request->duration;
            //     $secondSession->save();
            // }
            
            // 
            if($request->preference_questions == "1")
            {
                return redirect()->route('tournament_add',['id'=>$newTournament->id]);
            
            }
            else if ($request->preference_questions == "0")
            {
                // $tournament_questions = QuestionsSetting::where('domain_id','=', $request->domain_id)->pluck('id')->toArray();
                
                $newQuizeQuestions = new TournamentQuizeQuestion;
                // $newQuizeQuestions->questions_id = json_encode($tournament_questions);
                $newQuizeQuestions->tournament_id  = $newTournament->id;
                $newQuizeQuestions->total_no_question = 0;
                $newQuizeQuestions->question_type = '0';
                $newQuizeQuestions->save();
               // dd($tournament_questions);
               return redirect()->route('tournament.index');

            }
        }
        else
        {
            // Special Quiz
            
            $validatedData = $request->validate([
                'title' => 'required|',
                'age_group_id' => 'required|integer',
                'session_per_day' => 'required|integer',
                'start_time' => 'required',
                'no_of_players' => 'required|integer',
                'duration' => 'required|integer',
                'interval_bw_session' => 'required',
                'sponsor_media_name'=>'required',
                'no_of_question'=>'required',
                'mark_per_question'=>'required|integer',
                'media_name'=>'required',
    
            ]);
           
            $newTournament = new Tournament;
            $newTournament->title = $request->title;
            $newTournament->type = $request->quize_type;
            $newTournament->age_group_id = $request->age_group_id;
            $newTournament->frequency_id = $request->frequency_id;
            $newTournament->session_per_day =$request->session_per_day;
            $newTournament->no_players = $request->no_of_players;
            $newTournament->duration = $request->duration;
            $newTournament->start_time = $request->start_time;
            $newTournament->interval_session = $request->interval_bw_session;
            $newTournament->no_of_question = $request->no_of_question;
            $newTournament->marks_per_question = $request->mark_per_question;
            $newTournament->negative_marking = '1';//$request->negative_marking;
            $newTournament->negative_marking_per_question = $request->negative_mark_per_question;
            // if($request->hasfile('media_name'))
            // {
            //     $media_name = $request->file('media_name')->store('tournament','public');
            //     $newTournament->media_name = $media_name;
            // }
            if($request->hasFile('media_name'))
            {
                $sponsor_media_name = $request->file('media_name')->store('sponsor','public');
                $newTournament->sponsor_media_name = $sponsor_media_name;
            }
           $newTournament->save();

           
            
            // store excel file question 
            Excel::import(new TournamentQuestionImport($newTournament->id), $request->file('sponsor_media_name'));
            return back();
            
            //dd($newTournament);
           

        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tournament = Tournament::find($id);
        if ($tournament->status == '1') {
            $tournament->status = '0';
        } else {
            $tournament->status = '1';

        }
        $tournament->save();

        if ($tournament->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
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
     //dd($tournament);
        $validatedData = $request->validate([
            'title' => 'required|',
            // 'sub_domain_id' => 'required|integer',
            'quize_type' => 'required|integer',
            'age_group_id' => 'required|integer',
            'difficulty_level_id' => 'required|integer',
            // 'theme_id' => 'required|integer',
           // 'domain_id' => 'required|integer',
            'no_of_players' => 'required|integer',
            'duration' => 'required|integer',
            //'media_name' => 'required',
            //'sponsor_media_name'=>'required',
           'no_of_question'=>'required',

        ]);


        // add normal quize 
        if($request->quize_type == "0")
        {   
            if($request->frequency_id=='1'){
                $validatedData = $request->validate([
                    'session_per_day' => 'required|integer',
                ]);
                $interval_session = $request->interval_session;
                $session_per_day = $request->session_per_day;
            } else{
                $validatedData = $request->validate([
                    'is_attempt' => 'required|integer',

                ]);
                $interval_session =   1440;
                $session_per_day = 1;
            }

            $updateTournament =  Tournament::find($tournament->id);
            //dd($updateTournament);
         //   dd($updateTournament);
            $updateTournament->title = $request->title;
            $updateTournament->type = $request->quize_type;
            $updateTournament->age_group_id = $request->age_group_id;
            $updateTournament->difficulty_level_id = $request->difficulty_level_id;
            $updateTournament->theme_id = $request->theme_id;
           // $updateTournament->domain_id = $request->domain_id;
            $updateTournament->sub_domain_id = $request->sub_domain_id;
            $updateTournament->frequency_id = $request->frequency_id;
            $updateTournament->session_per_day = $session_per_day;
            $updateTournament->no_players = $request->no_of_players;
            $updateTournament->duration = $request->duration;
            //$updateTournament->start_time = $request->start_time;
            $updateTournament->is_attempt = $request->is_attempt;
            $updateTournament->no_of_question = $request->no_of_question;
           // $updateTournament->end_time = $request->end_time;
            $updateTournament->interval_session = $interval_session;

           // dd($updateTournament->save());
            if($request->hasfile('media_name'))
            {
                 // unlink(storage_path('app/folder/'.$updateTournament->media_name));
                $media_name = $request->file('media_name')->store('tournament','public');
                $updateTournament->media_name = $media_name;
            }
            if($request->hasFile('sponsor_media_name'))
            {
                $sponsor_media_name = $request->file('sponsor_media_name')->store('sponsor','public');
                $updateTournament->sponsor_media_id = $sponsor_media_name;
            }
            $updateTournament->save();
            //update save session for first time
            
            $old_sessions_per_day = SessionsPerDay::where('tournament_id','=',$tournament)->get();
            foreach($old_sessions_per_day as $old_session_per_day)
            {
                $old_session_per_day->delete();
            }
            $SessionsPerDay = new SessionsPerDay;
            $starttime = date('H:i',strtotime($request->start_time));
            $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));
            $SessionsPerDay->start_time =$starttime; 
            $SessionsPerDay->end_time = $endtime;
            $SessionsPerDay->duration = $request->duration;
            $SessionsPerDay->tournament_id = $updateTournament->id;
            $SessionsPerDay->save();
            $sess = $request->session_per_day-1;

            if($request->frequency_id=='1'){
             for($sess; $sess==0;$sess-- ){
                
                $starttime = date('H:i',strtotime("+$request->interval_session minutes", strtotime($endtime)));  
                $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));                
                
                $secondSession = new SessionsPerDay;
                $secondSession->start_time =$starttime; 
                $secondSession->end_time = $endtime;
                $secondSession->duration = $request->duration;
                $secondSession->tournament_id = $updateTournament->id;
                $secondSession->save();
             }
            
            }
            // if($request->frequency_id=='1'){

            //     $starttime = date('H:i',strtotime($request->start_time));
            //     $endtime = date('H:i',strtotime("+$request->duration minutes", strtotime($starttime)));
            //     $secondSession = new SessionsPerDay;
            //     $secondSession->start_time =$starttime; 
            //     $secondSession->end_time = $endtime;
            //     $secondSession->duration = $request->duration;
            //     $secondSession->save();
            // }
            
             return redirect()->route('tournament.index');
            if($request->preference_questions == "1")
            {
                return redirect()->route('tournament_add',['id'=>$newTournament->id]);
            
            }
            else if ($request->preference_questions == "0")
            {
                $tournament_questions = QuestionsSetting::where('domain_id','=', $request->domain_id)->pluck('id')->toArray();
                
                $newQuizeQuestions = new TournamentQuizeQuestion;
                $newQuizeQuestions->questions_id = json_encode($tournament_questions);
                $newQuizeQuestions->tournament_id  = $newTournament->id;
                $newQuizeQuestions->total_no_question = count($tournament_questions);
                $newQuizeQuestions->save();
               // dd($tournament_questions);
               return redirect()->route('tournament.index');

            }
        }
        else
        {
            $updateTournament =  Tournament::find($tournament);
            $updateTournament->title = $request->title;
            $updateTournament->type = $request->quize_type;
            $updateTournament->age_group_id = $request->age_group_id;
            $updateTournament->frequency_id = $request->frequency_id;
            $updateTournament->session_per_day =$request->session_per_day;
            $updateTournament->no_players = $request->no_of_players;
            $updateTournament->duration = $request->duration;
            $updateTournament->start_time = $request->start_time;
            $updateTournament->interval_session = $request->interval_bw_session;
            $updateTournament->no_of_question = $request->no_of_question;
            $updateTournament->marks_per_question = $request->mark_per_question;
            $updateTournament->negative_marking = '1';//$request->negative_marking;
            $updateTournament->negative_marking_per_question = $request->negative_mark_per_question;
            if($request->hasfile('media_name'))
            {
                $media_name = $request->file('media_name')->store('tournament','public');
                $updateTournament->media_name = $media_name;
            }
            if($request->hasFile('sponsor_media_name'))
            {
                $sponsor_media_name = $request->file('sponsor_media_name')->store('sponsor','public');
                $updateTournament->sponsor_media_name = $sponsor_media_name;
            }
           $updateTournament->save();

           return redirect()->route('tournament.index');
            
            // store excel file question 
           // Excel::import(new TournamentQuestionImport($updateTournament->id), $request->file('tournament_question_bluck'));
           // return back();
            
            //dd($newTournament);
           

        }
       






    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        if ($tournament->id) {
            return redirect()->back()->with(['success' => 'Tournament Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
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
        $newQuizeQuestions->question_type = '1';

        $newQuizeQuestions->save();

        return redirect()->route('tournament.index');
       // dd(json_encode($req->questions_id));
    }

    public function getDownloadExccelSheet()
    {
        //PDF file is stored under project/public/download/info.pdf
   
        $file=  storage_path()."\app\public\sponsor-sample.csv";
        //dd($file);
        $headers = array(
              'Content-Type: application/csv',
            );

            return response()->download($file, 'sponsor-sample.csv', $headers);   
    }

    public  function imageurl($image)
    {
    try {
        return url('/storage').'/'.$image;
    } catch (\Throwable $th) {
        return '';
    }

     }

    // get all tournament api 
    public function tournament(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
       ]);

       if ($validator->fails()) {
           return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
       } 
            $user = User::find($request->user_id);
            $age=Carbon::parse($user->dob)->age;
            
           $ageGroup=AgeGroup::where('from','<=',$age)->where('to','>=',$age)->first();
         
         if($request->search){
            $tournaments = Tournament::select('id','title','start_time','duration','interval_session','frequency_id','is_attempt','sponsor_media_id')
            ->where('title', 'like', '%' . $request->search . '%')
            ->where('age_group_id',$ageGroup->id)->OrderBy('id', 'DESC')->get();
         }else{
        //    dd($request->user_id);
        $tournaments = Tournament::select('id','title','start_time','duration','interval_session','frequency_id','is_attempt','sponsor_media_id')->where('age_group_id',$ageGroup->id)->OrderBy('id', 'DESC')->get();
         }
        //Post::with('user:id,username')->get();
        $currentDateTime = Carbon::now();
        
        $date=  $currentDateTime->toDateString();
         $time=  $currentDateTime->toTimeString(); 
         if(empty($tournaments)){
            return response()->json(['status' => 204, 'data' => array(), 'message' => 'No tournament found for the age group','date'=>$date,'time'=>$time]);
         }
        foreach($tournaments as $tournament)
        {
            
            $tournament->difficulty = Tournament::find($tournament->id)->difficulty_level->name;
            $tournament->frequency = Tournament::find($tournament->id)->frequency->title;
            $tournament->sessions = SessionsPerDay::select('start_time','id')->where('tournament_id',$tournament->id)->get()->toArray();

            $tournament->duration  = $tournament->duration;

            //  $tournament->sessions = SessionsPerDay::where('tournament_id',$tournament->id)->pluck('start_time','id')->toArray();
            //  $tournament->frequency = $tournament->frequency_id;
            $url_image = url('/storage').'/'.Tournament::find($tournament->id)->media_name;
            $tournament->image_url = $url_image;
            $tournament->sponsor_media_id = url('/storage').'/'.$tournament->sponsor_media_id;
             
            //Current day record
            if($tournament->frequency_id==1){
                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)->where('status','joined')->whereDate('created_at', Carbon::today())->first();
                if(empty($mytournamnet)){

                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)->where('status','completed')->whereDate('created_at', Carbon::today())->first();
                }
            }
            // Prevoius 7 days record
            if($tournament->frequency_id==2){
                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)
                ->where('status','joined')->where('created_at','>=',Carbon::now()->subdays(7))->first();
                if(empty($mytournamnet)){

                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)
                ->where('status','completed')->where('created_at','>=',Carbon::now()->subdays(7))->first();
                }
            }
                
            // // Last Month record
            // if($tournament->frequency_id==3){
            //     $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)->where('status','completed')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->first();
            // }

              // This Month record
            if($tournament->frequency_id==3){
                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)
                ->where('status','joined')->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->first();
if(empty($mytournamnet)){
                $mytournamnet = TournamenetUser::where('tournament_id',$tournament->id)->where('user_id',$request->user_id)
                ->where('status','completed')->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->first();
}
            }
          
            if($mytournamnet){
                $isset = 1;
            }else{
                $isset = 0;
            }
            $tournament->is_played = $isset ;
            // $tournament->is_attempt = $tournament->is_attempt;
          
            //
        }
     
        return response()->json(['status' => 200, 'data' => $tournaments, 'message' => 'Domain Data','date'=>$date,'time'=>$time]);
        

    }

    // Saved tournamnet or Start a Tournament
    public function start_tournament()
    {

        $tournaments = Tournament::select('id','title','start_time','duration','interval_session')->OrderBy('id', 'DESC')->get();
        //Post::with('user:id,username')->get();

        foreach($tournaments as $tournament)
        {
            $tournament->difficulty = Tournament::find($tournament->id)->difficulty_level->name;
            $tournament->frequency = Tournament::find($tournament->id)->frequency->title;
            $url_image = url('/storage').'/'.Tournament::find($tournament->id)->media_name;
            $tournament->image_url = $url_image;
            
        }
        $currentDateTime = Carbon::now();
        
       $date=  $currentDateTime->toDateString();
        $time=  $currentDateTime->toTimeString(); 
        return response()->json(['status' => 200, 'data' => $tournaments, 'message' => 'Domain Data','date'=>$date,'time'=>$time]);
        

    }

    

    public function tournament_rule(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'user_id' => 'required',
            'tournament_id' => 'required',
            'session_id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }  
        $tournament = Tournament::find($request->tournament_id);
        if (empty($tournament)) {
            return response()->json(['status' => 204, 'message' => 'Tournament expired or not found', 'data' => '']);
        }
        $TournamenetUser = TournamenetUser::where('user_id',$request->user_id)->where('session_id',$request->session_id)->where('tournament_id',$request->tournament_id)->whereDate('created_at', Carbon::today())->latest()->first();

         $question = TournamentSessionQuestion::where('session_id',$request->session_id)->where('tournament_id',$request->tournament_id)->whereDate('created_at', Carbon::today())->first();

         if(empty($question)){
         $response =  AddSessionQuestionJob::dispatchNow($request->tournament_id,$request->session_id);
         }
   
        if(empty($TournamenetUser)){
        $savetournament = new TournamenetUser;
        $savetournament->user_id = $request->user_id;
        $savetournament->tournament_id = $request->tournament_id;
        $savetournament->session_id = $request->session_id;
        $savetournament->status='joined';
        $savetournament->save();
        }

        $quiz_rules = TournamentRule::where('tournament_id',$request->tournament_id);
        $data = json_decode($quiz_rules->details);
        if (empty($quiz_rules)) {
            return response()->json(['status' => 204, 'message' => 'No rules found for the quiz', 'data' => '']);
        } else {
            return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
        }
    }
 

    public function join_tournament(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'user_id' => 'required',
            'tournament_id' => 'required',
            'session_id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }  
        $tournament = Tournament::find($request->tournament_id);

        if (empty($tournament)) {
            return response()->json(['status' => 204, 'message' => 'Tournament expired or not found', 'data' => '']);
        }
        $tournamenetUser = TournamenetUser::where('user_id',$request->user_id)->where('session_id',$request->session_id)->where('tournament_id',$request->tournament_id)->whereDate('created_at', Carbon::today())->latest()->first();
       
        //  TournamentSessionQuestion::find();
         $question = TournamentSessionQuestion::where('session_id',$request->session_id)->where('tournament_id',$request->tournament_id)->whereDate('created_at', Carbon::today())->first();

         if(empty($question)){
            AddSessionQuestionJob::dispatchNow($request->tournament_id,$request->session_id);
         }
        if(empty($tournamenetUser)){

        $savetournament = new TournamenetUser;
        $savetournament->user_id = $request->user_id;
        $savetournament->tournament_id = $request->tournament_id;
        $savetournament->session_id = $request->session_id;
        $savetournament->status='joined';
        $savetournament->save();

        } else{
            return response()->json(['status' => 200, 'message' => 'User joined already', 'data' => $tournamenetUser]);
        }
       
        if (empty($savetournament)) {
            return response()->json(['status' => 204, 'message' => 'Something went wrong', 'data' => '']);
        } else {
            return response()->json(['status' => 200, 'message' => 'User joined succesfully', 'data' => $savetournament]);
        }
    }

  

}
