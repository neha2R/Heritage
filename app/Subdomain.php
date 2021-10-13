<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subdomain extends Model
{
    use SoftDeletes;

    //

    public function domain()
    {
        return $this->belongsTo('App\Domain', 'id', 'domain_id');
    }
}
