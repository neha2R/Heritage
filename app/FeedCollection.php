<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedCollection extends Model
{
    //
    protected $table = 'feed_collection';
    public function feed_content()
    {
        return $this->hasOne('App\FeedContent', 'id','feed_content_id');
    }

    public function single_post()
    {
        return $this->hasOne('App\FeedContent', 'id','feed_content_id');
    }
}
