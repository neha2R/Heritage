<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tournament;
use App\SessionsPerDay;
use Carbon\Carbon;
use App\TournamenetUser;
use App\User;
class TournamentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournoti:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to user who registered the tournament';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $starttime = date('H:i', strtotime("+2 min"));
    //    echo $starttime;
    //    exit();
        $tournaments = Tournament::select('id', 'title', 'start_time', 'duration', 'interval_session', 'frequency_id', 'is_attempt', 'sponsor_media_id')->where('status', '1')->OrderBy('id', 'DESC')->get();
       $users=[];
       
        if($tournaments){
        foreach ($tournaments as $tournament) {
            $sessid = SessionsPerDay::select('id')->where('tournament_id', $tournament->id)
            ->where('start_time', $starttime)->first();
                // $userids=null;
                if($sessid){
                    $users[] = TournamenetUser::where('tournament_id', $tournament->id)->where('session_id', $sessid->id)->where('status', 'joined')->whereDate('created_at', Carbon::today())->pluck('user_id')->toArray();
                } 
                // if($userids != null){
                //     $users[] = $userids;
                // }
        
        }
    }
    dd($users);
if(!empty($users)){
    
        foreach ($users as $user){
            $data = [
                'title' => 'Tournament Reminder.',
                'token' => User::where('id', $user)->first()->token,
                'type' => 'tournament',
                'link' => '',
                'message' => 'Your tournament is about to start',
            ];
            sendNotification($data);
        }
    }
        return 0;
    }
}
