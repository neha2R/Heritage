<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Tournament;
use Carbon\Carbon;
use App\TournamenetUser;
use App\MonthendXp;

class ConvertXptoLp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xptolp:convertxptolp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End of month all users lp chnages to xp';

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

        $users = User::where('type','2')->get();
        
        foreach ($users as $user) {
            $group = age_group_by_user($user->id);
    if($group){
            $daily = Tournament::where('age_group_id', $group->id)->where('frequency_id', 1)->count();
            $weekly = Tournament::where('age_group_id', $group->id)->where('frequency_id', 2)->count();
            $month = Tournament::where('age_group_id', $group->id)->where('frequency_id', 3)->count();
            $week = Carbon::now()->weekOfMonth;
            $day =  Carbon::now()->day;
            $totaltour = ($daily * $day) + ($weekly * $week) + $month;
            $totallp = $totaltour * $totaltour;
            $userTours = TournamenetUser::selectRaw("SUM(lp) as cu_lp")->where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->first();
            $count1 = $userTours->cu_lp / $totallp;
            $count2 = $count1 * 100;
            $percentage = number_format($count2, 0);
            if ($percentage >= 0 && $percentage <= 30) {

                $userleague['title'] = 'Initiate';
                $userleague['id'] = 5;
                $userleague['xp'] = 400;
            }
            if ($percentage >= 31 && $percentage <= 50) {
                $userleague['title'] = 'Dabbler';
                $userleague['id'] = 4;
                $userleague['xp'] = 800;
            }
            if ($percentage >= 51 && $percentage <= 70) {
                $userleague['title'] = 'Scholar';
                $userleague['id'] = 3;
                $userleague['xp'] = 1200;
            }
            if ($percentage >= 71 && $percentage <= 90) {
                $userleague['title'] = 'Culture Vulture';
                $userleague['id'] = 2;
                $userleague['xp'] = 1600;
            }
            if ($percentage >= 91 && $percentage <= 100) {
                $userleague['title'] = 'Expert';
                $userleague['id'] = 1;
                $userleague['xp'] = 2000;
            }
            $savexp =   new MonthendXp;
            $savexp->xp = $userleague['xp'];
            $savexp->league_id = $userleague['id'];
            $savexp->user_id = $user->id;
            $savexp->save();
        }
        }
        echo 'Successfully run the command on server which chnages lp to xp';
    }
}
