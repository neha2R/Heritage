<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    //
    protected $fillable = ['name'];

    public function subdomain()
    {
        return $this->hasMany('App\Subdomain', 'domain_id', 'id');
    }

}
