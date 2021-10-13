<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizDomain extends Model
{
    public function domain()
    {
        return $this->hasOne('App\Domain', 'id', 'domain_id');
    }
}
