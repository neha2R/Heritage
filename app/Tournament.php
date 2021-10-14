<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tournament extends Model
{
    //
   use SoftDeletes;
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

    public function frequency()
    {
        return $this->hasOne('App\Frequency', 'id','frequency_id');
    }

    
    
}
