<?php

namespace App\Jobs;

use App\Performance;
use App\QuizQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        $questions = QuizQuestion::where('attempts_id', $respreformance['quiz_id'])->first('questions')->toArray();

        $ans = explode(",", $respreformance['quiz_answer']);
        $question = explode(",", $questions['questions']);
        // dd($ans);
        foreach ($ans as $key => $myperformance) {
            $saveperformance = new Performance;
            $saveperformance->attempt_id = $respreformance['quiz_id'];
            $saveperformance->selected_option = $myperformance;
            $saveperformance->question_id = $question[$key];
            // $saveperformance->result = 2;
            $saveperformance->save();

        }

    }
}
