<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class QuestionsSetting extends Model
{
    
    protected $table = 'questions_setting';
    public function age_group()
    {
        return $this->belongsTo('App\AgeGroup', 'age_group_id', 'id');
    }

    public function difflevel()
    {
        return $this->belongsTo('App\DifficultyLevel', 'difficulty_level_id', 'id');
    }

    public function domain()
    {
        return $this->belongsTo('App\Domain', 'domain_id', 'id');
    }
    public function subdomain()
    {
        return $this->hasMany('App\Subdomain', 'id', 'Subdomain_id');
    }

}
