<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = ['challange_id'];
    
    public function dual_domain()
    {
        return $this->hasOne('App\QuizDomain', 'attempts_id', 'id')->with('domain');
    }
    public function quiz_speed()
    {
        return $this->hasOne('App\QuizSpeed', 'id', 'quiz_speed_id');
    }
    public function quiz_type()
    {
        return $this->hasOne('App\QuizType', 'id', 'quiz_type_id');
    }
    public function difficulty()
    {
        return $this->hasOne('App\DifficultyLevel', 'id', 'difficulty_level_id');
    }
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
