<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    //

    protected $table = 'tournament';
    public function theme()
    {
        return $this->hasOne('App\Theme', 'id','theme_id');
    }

    public function age_group()
    {
        return $this->hasOne('App\AgeGroup', 'id','age_group_id');
    }

    public function difficulty_level()
    {
        return $this->hasOne('App\DifficultyLevel', 'id','difficulty_level_id');
    }

    public function domain()
    {
        return $this->hasOne('App\Domain', 'id','domain_id');
    }

    public function sub_domain()
    {
        return $this->hasOne('App\Subdomain', 'id','sub_domain_id');
    }

    // public function frequency_id()
    // {
    //     return $this->hasOne('App\Subdomain', 'id','sub_domain_id');
    // }

    
    
}
