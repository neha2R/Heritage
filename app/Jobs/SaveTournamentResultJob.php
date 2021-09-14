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
        $user = TournamenetUser::where('tournament_id',$result['tournament_id'])->where('session_id', $result['session_id'])->first();
        $questions = TournamentSessionQuestion::where('tournament_id', $result['tournament_id'])->where('session_id', $result['session_id'])->first('questions');
        if (empty($questions)) {

            return 'error';
        } else {
            $questions = $questions->toArray();
        }
        $ans = explode(",", $result['answer']);
        $question = json_decode($questions['questions']);
        // dd($ans);
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
                } else {
                    $saveperformance->result = '0';
                }
            }else{
                $saveperformance->result =null;
            }
            $saveperformance->save();

        }
        $user->status = 'completed';
        $user->save();
        return 'success';
    }
}
