<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function questionsetting()
    {
        return $this->hasOne('App\QuestionsSetting', 'question_id', 'id')->with('domain', 'difflevel', 'age_group');
    }
}
