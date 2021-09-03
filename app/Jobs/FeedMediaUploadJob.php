<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FeedMediaUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $media_id,$file,$type;
    public function __construct($file,$media_id,$type)
    {
        //
        $this->file = $file;
        $this->media_id = $media_id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $name = $this->file->store('feed','public');
        $attachment = new FeedAttachment;
        $attachment->feed_media_id = $this->media_id;
        $attachment->media_name = $name;
        $attachment->media_type = $type;
        $attachment->save();
    }
}
