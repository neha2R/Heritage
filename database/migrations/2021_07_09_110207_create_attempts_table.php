<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('difficulty_level_id')->constrained();
            $table->foreignId('quiz_type_id')->constrained();
            $table->foreignId('quiz_speed_id')->constrained();
            $table->integer('attempt')->comment('1=> for singal 2=>second')->nullable();
            $table->integer('challange_id')->comment('Challange table id')->nullable();
            $table->string('status')->comment('status of exam , completed,notcompleted')->nullable();
            $table->string('result')->comment('Pass, fail etc')->nullable();
            $table->string('marks')->comment('Obtain Marks')->nullable();
            $table->dateTime('started_at')->comment('Exam started')->nullable();
            $table->dateTime('end_at')->comment('Exam ended or submited')->nullable();
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
        Schema::dropIfExists('attempts');
    }
}
