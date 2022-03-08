<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    public function badgedata()
    {
        return $this->hasOne('App\Badge', 'id', 'badge_id');
    }
}
