<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('quiz_type_id')->nullable();
            $table->integer('quiz_speed_id')->nullable();
            $table->text('scoring')->nullable();
            $table->text('negative_marking')->nullable();
            $table->text('time_limit')->nullable();
            $table->text('no_of_players')->nullable();
            $table->text('hint_guide')->nullable();
            $table->text('que_navigation')->nullable();
            $table->text('more')->nullable();
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
        Schema::dropIfExists('quiz_rules');
    }
}
