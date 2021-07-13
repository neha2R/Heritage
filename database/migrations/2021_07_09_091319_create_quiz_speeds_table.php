<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizSpeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_speeds', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->integer('no_of_question')->nullable();	
            // $table->integer('duration')->comment('in minutes')->nullable();	
            // $table->integer('time_per_question')->comment('in seconds')->nullable();	
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
        Schema::dropIfExists('quiz_speeds');
    }
}
