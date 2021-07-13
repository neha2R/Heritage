<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    //
    protected $fillable = ['name'];

    public function subdomain()
    {
        return $this->hasMany('App\Subdomain', 'domain_id', 'id');
    }

}
