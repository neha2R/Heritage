<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_question', function (Blueprint $table) {
            $table->id();
            $table->string('question',250)->nullable();
            $table->string('question_img',200)->nullable();
            $table->string('keyword',200)->nullable();
            $table->string('explanation',200)->nullable();
            $table->string('answer',200)->nullable();
            $table->string('answer_img',200)->nullable();
            $table->string('option_1',200)->nullable();
            $table->string('option_1_img',200)->nullable();
            $table->string('option_2',200)->nullable();
            $table->string('option_2_img',200)->nullable();
            $table->string('option_3',200)->nullable();
            $table->string('option_3_img',200)->nullable();
            $table->string('option_4',200)->nullable();
            $table->string('option_4_img',200)->nullable();

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
        Schema::dropIfExists('tournament_question');
    }
}
