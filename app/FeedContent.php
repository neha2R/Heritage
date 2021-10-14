<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedContent extends Model
{
    //feedtype
    protected $fillable = ['theme_id'];   
    public function feedtype()
    {
        return $this->hasOne('App\Feed', 'id','feed_id');
    }

    public function theme()
    {
        return $this->hasOne('App\Theme', 'id','theme_id');
    }

    public function domain()
    {
        return $this->hasOne('App\Domain','id','domain_id');
    }
    public function feed_media()
    {
        return $this->hasMany('App\FeedMedia', 'feed_content_id','id')->with('feed_attachments');
    }
    public function feed_medium()
    {
        return $this->hasOne('App\FeedMedia', 'feed_content_id','id')->with('feed_attachments');
    }

    public function feed_media_single()
    {
        return $this->hasOne('App\FeedMedia', 'feed_content_id','id')->with('feed_attachments_single');
    }
    
  

    public function savefeed()
    {
        return $this->hasOne('App\SaveFeed', 'id','feed_contents_id');
    }

}
