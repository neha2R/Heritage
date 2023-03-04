<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CheckUserOnline;

use Carbon\Carbon;
use App\User;
class OnlineNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onlinestatus:send';

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
      //  $starttime = date('H:i', strtotime("+2 min"));
    //    echo $starttime;
    //    exit();
  
        $checkonlines = CheckUserOnline::OrderBy('id', 'DESC')->get();
        foreach ($checkonlines as $checkonline) {
            if (Carbon::now()->parse($checkonline->updated_at)->diffInSeconds() >45) { 
                $checkonline->is_online='2';
                $checkonline->save();
                // Duel is not older than 3 minute
           }
        }
        
       
 
  

        return 0;
    }
}
