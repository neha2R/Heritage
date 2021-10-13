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
use App\QuestionsSetting;

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
        $attempt = Attempt::find($respreformance['quiz_id']);
        if (empty($questions)) {

            return 'error';
        } else {
            $questions = $questions->toArray();
        }
        $ans = explode(",", $respreformance['quiz_answer']);
        $question = explode(",", $questions['questions']);
        // dd($ans);
        $marks=0;
        $total=0;
        foreach ($ans as $key => $myperformance) {
            $saveperformance = new Performance;
            $saveperformance->attempt_id = $respreformance['quiz_id'];
            $saveperformance->selected_option = $myperformance;
            $saveperformance->question_id = $question[$key];
            $ques = Question::find($question[$key]);
            $queSetting = QuestionsSetting::where('question_id',$question[$key])->first();
            $total += $queSetting->difflevel->weitage_per_question;
            if ($myperformance != 0) {
                if ($ques->right_option == $myperformance) {
                    $saveperformance->result = 1;
                    $marks += $queSetting->difflevel->weitage_per_question;
                } else {
                    $saveperformance->result = 0;
                }
            }
            $saveperformance->save();

        }
        $count1 = $marks / $total;
        $count2 = $count1 * 100;
        $percentage = number_format($count2, 0);
        if($percentage>=80){
            $xp = 50;
        }
        if($percentage>=80){
            $xp = 50;
        }
        if($percentage<80 && $percentage>=60){
            $xp = 40;
        } 
        if($percentage<60 && $percentage>=40){
            $xp = 30;
        } 
        if($percentage<40 && $percentage>=20){
            $xp = 20;
        } 
        if($percentage<20 ){
            $xp = 0;
        } 
        $attempt->status = 'completed';
        $attempt->marks= $marks;
        $attempt->result= $percentage;
        $attempt->xp= $xp;
        $attempt->end_at= date('Y-m-d h:i:s');
        $attempt->save();
        return 'success';

    }
}
