<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleTitleToFeedContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_contents', function (Blueprint $table) {
            $table->string('title')->nullable()->comment('Main title of a feed');
            $table->longText('description')->nullable()->comment('Main description of a feed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_contents', function (Blueprint $table) {
            //
        });
    }
}
