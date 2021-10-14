<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_content_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('external_link')->nullable()->comment('External page link for the feed');
              
            $table->string('video_link')->nullable()->comment('External video link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_media');
    }
}
