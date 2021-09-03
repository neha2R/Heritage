<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentQuizeQuestion extends Model
{
    //
    protected $table = 'tournament_quize_questions';
    protected $fillable = ['tournament_id','questions_id','total_no_question'];
    

    public function tournament()
    {
        return $this->hasOne('App\Tournament', 'id','tournament_id');
    }
}
