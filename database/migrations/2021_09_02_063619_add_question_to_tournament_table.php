<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionToTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament', function (Blueprint $table) {
            //
            $table->integer('no_of_question')->nullable();
            $table->integer('marks_per_question')->nullable();
            $table->integer('negative_marking')->nullable();
            $table->integer('negative_marking_per_question')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament', function (Blueprint $table) {
            //
            $table->dropColumn('no_of_question');
            $table->dropColumn('marks_per_question');
            $table->dropColumn('negative_marking');
            $table->dropColumn('negative_marking_per_question');
        });
    }
}
