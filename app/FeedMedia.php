<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedMedia extends Model
{
    public function feed_attachments()
    {
        return $this->hasMany('App\FeedAttachment', 'feed_media_id','id');
    }
}
