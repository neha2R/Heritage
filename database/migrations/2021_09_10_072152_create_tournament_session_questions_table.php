<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentSessionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_session_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('tournament_id')->nullable()->comment('Tournament table id');
            $table->integer('session_id')->nullable()->comment('sessions per days table id');
            $table->text('questions')->nullable()->comment('questions of tournament per session');
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
        Schema::dropIfExists('tournament_session_questions');
    }
}
