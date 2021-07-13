<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
           $table->string('question');
            $table->string('question_media')->nullable();	
            $table->string('option1');
            $table->string('option1_media')->nullable();
             $table->string('option2');
            $table->string('option2_media')->nullable();
            $table->string('option3');
            $table->string('option3_media')->nullable(); 
            $table->string('option4');
            $table->string('option4_media')->nullable(); 
            $table->string('why_right');
            $table->string('why_right_media')->nullable(); 
            $table->integer('right_option')->comment('1,2,3,4')->nullable();
            $table->string('hint')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
