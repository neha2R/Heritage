<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_media_id')->nullable();
            $table->enum('media_type',['0','1',])->default(0)->comment('0 image, 1 for using video');
            $table->string('media_name')->nullable();
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
        Schema::dropIfExists('feed_attachments');
    }
}
