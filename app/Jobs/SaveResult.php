<?php

namespace App\Jobs;

use App\Performance;
use App\Question;
use App\QuizQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Attempt;

class SaveResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $performance = [];
    protected $questions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($performance)
    {
        $this->performance = $performance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $respreformance = $this->performance;

        $questions = QuizQuestion::where('attempts_id', $respreformance['quiz_id'])->first('questions');
        $attempt = Attempt::find($$respreformance['quiz_id']);
        if (empty($questions)) {

            return 'error';
        } else {
            $questions = $questions->toArray();
        }
        $ans = explode(",", $respreformance['quiz_answer']);
        $question = explode(",", $questions['questions']);
        // dd($ans);
        foreach ($ans as $key => $myperformance) {
            $saveperformance = new Performance;
            $saveperformance->attempt_id = $respreformance['quiz_id'];
            $saveperformance->selected_option = $myperformance;
            $saveperformance->question_id = $question[$key];
            $ques = Question::find($question[$key]);
            if ($myperformance != 0) {
                if ($ques->right_option == $myperformance) {
                    $saveperformance->result = 1;
                } else {
                    $saveperformance->result = 0;
                }
            }
            $saveperformance->save();

        }
        return 'success';

    }
}
