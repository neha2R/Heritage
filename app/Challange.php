<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challange extends Model
{
    public function to_user()
    {
        return $this->hasOne('App\User', 'id', 'to_user_id');
    }
    public function from_user()
    {
        return $this->hasOne('App\User', 'id', 'from_user_id');
    }
}
