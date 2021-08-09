<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public function questionsetting()
    {
        return $this->hasOne('App\QuestionsSetting', 'question_id', 'id')->with('domain', 'difflevel', 'age_group');
    }

    public function questionsettingapi()
    {
        return $this->hasOne('App\QuestionsSetting', 'question_id', 'id');
    }

}
