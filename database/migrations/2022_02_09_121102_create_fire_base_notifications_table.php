<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFireBaseNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire_base_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('title')->nullable();
            $table->string('token')->nullable();
            $table->string('link')->nullable();
            $table->string('message')->nullable();
            $table->string('type')->nullable()->comment('dual,contact,quizroom');
            $table->enum('status',['0','1'])->default(0)->comment('0 for unread 1 for read');
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
        Schema::dropIfExists('fire_base_notifications');
    }
}
