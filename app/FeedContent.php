<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedContent extends Model
{
    //feedtype
    public function feedtype()
    {
        return $this->hasOne('App\Feed', 'id','feed_id');
    }

    public function theme()
    {
        return $this->hasOne('App\Theme', 'id','theme_id');
    }

    public function feed_media()
    {
        return $this->hasMany('App\FeedMedia', 'feed_content_id','id')->with('feed_attachments');
    }
}
