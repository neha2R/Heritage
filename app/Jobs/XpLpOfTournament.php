<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\TournamenetUser;

class XpLpOfTournament implements ShouldQueue
{
    protected $result = [];
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->result);
        
        $result = $this->result;
        // dd($result);
        $tournamentUsers = TournamenetUser::select('user_id','marks','percentage','lp','rank')->where('tournament_id',$result['tournament_id'])->where('session_id', $result['session_id'])->orderBy('percentage','ASC')->where('status','completed')->whereDate('created_at', Carbon::today())->get();
        $newrank = array();
        $i = 0;
        $last_v = null;
       foreach($tournamentUsers as $key=>$user){
           
        if ($user->marks != $last_v) {
            $i++;
            $last_v = $user->marks;
        }
        $newrank[$user->user_id] = $i;
        if($i==1){
            $lp = 50;
        }
        if($i==2){
            $lp = 40;
        }
        if($i==3){
            $lp = 30;
        }
        if($i>=4 && $i<=10){
            $lp = 20;
        } 
        if($i>10){
            $lp = 10;
        }
          $singleuser = TournamenetUser::where('tournament_id',$result['tournament_id'])->where('session_id', $result['session_id'])->where('user_id', $user->user_id)->orderBy('marks','DESC')->where('status','completed')->whereDate('created_at', Carbon::today())->first();
        $singleuser->rank = $i;
        $singleuser->lp = $lp;
        $singleuser->save();
      

       }
       
 
    }
}
