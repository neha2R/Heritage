<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\TournamentSessionQuestion;
use App\TournamentPerformance;
use App\TournamenetUser;
use App\Question;
use App\Tournament;

class SaveTournamentResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $result = [];
    protected $questions;
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
        $result = $this->result;
        // TournamenetUser
        $tournament = Tournament::find($result['tournament_id']);
        $user = TournamenetUser::where('tournament_id',$result['tournament_id'])->where('session_id', $result['session_id'])->where('user_id', $result['user_id'])->orderBy('id','DESC')->first();
       
        // if($user->status=='completed'){
        //  $data=array('status'=>'success','per'=> $user->percentage );
        //   return  $data; 
        // }
        
        $questions = TournamentSessionQuestion::where('tournament_id', $result['tournament_id'])->where('session_id', $result['session_id'])->orderBy('id','DESC')->first('questions');
        if (empty($questions)) {

            return 'error';
        } else {
            $questions = $questions->toArray();
        }
        $ans = explode(",", $result['answer']);
        $question = json_decode($questions['questions']);
        // dd($ans);
        $marks=0;
        foreach ($ans as $key => $myperformance) {
            $saveperformance = new TournamentPerformance;
            $saveperformance->tournamenet_users_id
            = $user->id;
            $saveperformance->selected_option = $myperformance;
            $saveperformance->question_id = $question[$key];
            $ques = Question::find($question[$key]);
            
            if ($myperformance != 0) {
                if ($ques->right_option == $myperformance) {
                    $saveperformance->result = '1';
                    $marks++;
                } else {
                    $saveperformance->result = '0';
                }
            }else{
                $saveperformance->result =null;
            }
           // $saveperformance->save();

        }

        if($tournament->type=='0'){
            $newmarks = $marks*$tournament->difficulty_level->weitage_per_question;

            $total = $tournament->difficulty_level->weitage_per_question*$tournament->no_of_question;

        }else{
            // For specila quiz
            $newmarks = $marks*$tournament->marks_per_question;
        $total = $tournament->marks_per_question*$tournament->no_of_question;
        }
        $count1 = $newmarks / $total;
        $count2 = $count1 * 100;
        $percentage = number_format($count2, 0);

        $user->status = 'completed';
        $user->marks= $newmarks;
        $user->percentage= $percentage;
        $user->save();
        $data=array('status'=>'success','per'=> $percentage );
        return  $data;
       
    }
}
