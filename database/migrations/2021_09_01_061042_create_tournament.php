<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournament extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->foreignId('age_group_id')->nullable();
            $table->foreignId('difficulty_level_id')->nullable();
            $table->foreignId('theme_id')->nullable();
            $table->foreignId('domain_id')->nullable();
            $table->foreignId('sub_domain_id')->nullable();
            $table->foreignId('frequency_id')->nullable();
            $table->integer('session_per_day');
            $table->integer('no_players');
            $table->integer('duration');
            $table->timestamp('start_time');
            $table->integer('interval_session');
            $table->string('media_name',200);
            $table->enum('type',['0','1',])->default(0)->comment('0 for normal, 1 for special');
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
        Schema::dropIfExists('tournament');
    }
}
