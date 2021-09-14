<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_performances', function (Blueprint $table) {
            $table->id();
            $table->integer('tournamenet_users_id')->nullable()->comment('Tournament users table id');
            $table->integer('question_id')->nullable()->comment('Tournament users table id'); 
            $table->integer('selected_option')->nullable()->comment('0=> for not attempt other=>1,2,3,4'); 
            $table->enum('result',['0','1',])->nullable()->comment('0=> for not attempt option is not right 1=> if attempted option is right');  
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
        Schema::dropIfExists('tournament_performances');
    }
}
