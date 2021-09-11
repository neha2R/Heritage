<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToNullableInTournamentQuizeQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_quize_questions', function (Blueprint $table) {
            $table->text('questions_id')->nullable()->change();
            $table->enum('question_type',['0','1',])->default(0)->comment('0 Random question from question table according to domain, 1 selected questions in questions_id fields');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_quize_questions', function (Blueprint $table) {
            //
        });
    }
}
