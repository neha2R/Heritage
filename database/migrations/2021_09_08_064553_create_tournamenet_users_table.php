<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamenetUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournamenet_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id');
            $table->foreignId('user_id')->constrained();
            $table->string('status')->comment('status of tournament , completed,notcompleted')->nullable();
            $table->string('marks')->comment('Obtain Marks')->nullable();
            $table->dateTime('started_at')->comment('Tournament started')->nullable();
            $table->dateTime('end_at')->comment('Tournament ended or submited')->nullable();
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
        Schema::dropIfExists('tournamenet_users');
    }
}
