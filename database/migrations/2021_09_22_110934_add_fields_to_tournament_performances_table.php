<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTournamentPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournamenet_users', function (Blueprint $table) {
            $table->integer('lp')->nullable()->comment('League point on the basis of rank');
            $table->integer('rank')->nullable()->comment('Rank of user according to  tournament');
            $table->integer('percentage')->nullable()->comment('Percentage of user according to  tournament');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_performances', function (Blueprint $table) {
            //
        });
    }
}
