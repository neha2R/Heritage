<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('friend_one')->comment('Jiss ne request ki');
            $table->integer('friend_two')->comment('Jiss ko request ki');
            $table->softDeletes();
            $table->enum('status',['0','1'])->default(0)->comment('0 for reject 1 for accept');
            $table->string('invited_via')->comment('invited via link or direct');
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
        Schema::dropIfExists('contacts');
    }
}
