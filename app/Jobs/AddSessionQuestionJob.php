<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Tournament;
use App\TournamentSessionQuestion;
use App\QuestionsSetting;
use App\TournamentQuizeQuestion;
use App\Question;

class AddSessionQuestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tornamnetid,$sessionid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public function __construct($tornamnetid,$sessionid)
    {
       
        $this->tornamnetid = $tornamnetid;
        $this->sessionid = $sessionid;
        // dd($this->tornamnetid);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tournament = Tournament::find($this->tornamnetid);

        $question = TournamentSessionQuestion::where('session_id',$this->sessionid)->where('tournament_id',$this->tornamnetid)->first();
        if(empty($question)){
           
            $tourQuestions = TournamentQuizeQuestion::where('tournament_id',$this->tornamnetid)->first();

            if($tourQuestions->question_type=='0'){
            $tournament_questions = QuestionsSetting::where('domain_id','=', $tournament->domain_id)->where('age_group_id', '=', $tournament->age_group_id)->inRandomOrder()->limit($tournament->no_of_question)->pluck('question_id')->toArray();

             $questions_ids = $tournament_questions;
         
            }
            else{
        
                $questions_ids = json_decode($tourQuestions->questions_id);
            // $questions_ids = json_decode($tourQuestions->questions_id);
                    
            $questions_ids =array_flip($questions_ids);                
            $questions_ids =array_rand($questions_ids,$tournament->no_of_question);
    
            }
           
          shuffle($questions_ids);

         $newQuizeQuestions = new TournamentSessionQuestion;
         $newQuizeQuestions->questions = json_encode($questions_ids);
         $newQuizeQuestions->tournament_id  = $this->tornamnetid;
         $newQuizeQuestions->session_id = $this->sessionid;
         $newQuizeQuestions->save();

        }
         else{
            $questions_ids = json_decode($question->questions);

        }
        $questions=[];
        foreach($questions_ids as $que){
           $data =  Question::select('id', 'question', 'question_media', 'option1', 'option1_media', 'option2', 'option2_media', 'option3', 'option3_media', 'option4', 'option4_media', 'why_right', 'right_option', 'hint', 'question_media_type')->where('id', $que)->first()->toArray();
           $questions[] = $data; 
        }
        // $questions = ;

        return $questions;
    }
}
