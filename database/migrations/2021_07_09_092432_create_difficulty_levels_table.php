<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDifficultyLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('difficulty_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->integer('weitage_per_question')->nullable();	
            $table->integer('time_per_question')->comment('in seconds')->nullable();	
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
        Schema::dropIfExists('difficulty_levels');
    }
}
