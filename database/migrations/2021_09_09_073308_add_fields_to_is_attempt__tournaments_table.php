<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToIsAttemptTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament', function (Blueprint $table) {
         
            $table->enum('is_attempt',['0','1',])->default(0)->comment('0 for user play once in a day, 1 for user play once in a week or month');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('is_attempt__tournaments', function (Blueprint $table) {
            //
        });
    }
}
