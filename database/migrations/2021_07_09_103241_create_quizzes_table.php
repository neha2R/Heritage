<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->integer('no_of_player')->nullable();	
            $table->foreignId('quiz_type_id')->constrained();
            // $table->foreignId('difficulty_level_id')->constrained();
            // $table->foreignId('quiz_speed_id')->constrained();
            $table->enum('hint',['0','1'])->nullable();
            $table->enum('negative_marking',['0','1'])->nullable();
            $table->integer('no_of_question')->comment('question on which negative marking  ex=>3')->nullable();
            $table->integer('marks')->comment('Marks deduct on quesstion ex=>1 ')->nullable();
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
        Schema::dropIfExists('quizzes');
    }
}
