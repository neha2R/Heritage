<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use SoftDeletes;

    protected $guarded=[];
    public function questionsetting()
    {
        return $this->hasOne('App\QuestionsSetting', 'question_id', 'id')->with('domain', 'difflevel', 'age_group');
    }
  
}
