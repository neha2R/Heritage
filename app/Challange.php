<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challange extends Model
{
    use SoftDeletes;
    protected $fillable = ['to_user_id'];

    public function to_user()
    {
        return $this->hasOne('App\User', 'id', 'to_user_id');
    }
    public function from_user()
    {
        return $this->hasOne('App\User', 'id', 'from_user_id');
    }
}
