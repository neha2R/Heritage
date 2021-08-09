<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldQuizSpeedTypeToQuizSpeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_speeds', function (Blueprint $table) {
            $table->enum('quiz_speed_type',['single','all'])->default('single')->comment('single = per question speed time, all= whole quiz in same speed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_speeds', function (Blueprint $table) {
            //
        });
    }
}
