<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentQuestions extends Model
{
    //
    protected $table = 'tournament_question';
    protected $fillable = ['question','question_img','keyword','explanation','answer','answer_img','option_1','option_1_img','option_2','option_2_img','option_3','option_3_img','option_4','option_4_img','tournament_id'];
    

    public function tournament()
    {
        return $this->hasOne('App\Tournament', 'id','tournament_id');
    }
}
