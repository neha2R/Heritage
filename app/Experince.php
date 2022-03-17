<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experince extends Model
{
    use SoftDeletes;
    public function images()
    {
        return $this->hasMany('App\ExperinceImage', 'experinces_id', 'id');
    }
}
