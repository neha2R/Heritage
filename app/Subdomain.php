<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    //

    public function domain()
        {
            return $this->belongsTo('App\Domain', 'id', 'domain_id');
        }
}
