<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->nullable();
            $table->foreignId('theme_id')->nullable();
            $table->foreignId('domain_id')->nullable();
            $table->enum('type',['0','1',])->default(0)->comment('0 for new template, 1 for using existing feeds');
            $table->string('tags')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('feed_contents');
    }
}
