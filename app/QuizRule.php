<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class QuizRule extends Model
{
    use SoftDeletes;

    public function types()
    {
        return $this->hasOne('App\QuizType', 'id','quiz_type_id');
    }

    public function speeds()
    {
        return $this->hasOne('App\QuizSpeed','id','quiz_speed_id');
    }
}
