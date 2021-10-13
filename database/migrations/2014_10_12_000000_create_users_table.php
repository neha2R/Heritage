<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('type', ['0', '1', '2'])->comment('0=>admin,1=>manager,2=>user');
            $table->date('dob');
            $table->string('mobile', 13)->nullable();
            $table->enum('is_social', ['0', '1'])->comment('0=>Not Register with social login, 1=social login');
            $table->string('token')->comment('token for send notification')->nullable();
            $table->string('app_id')->comment('for social login')->nullable();
            $table->integer('age')->nullable();
            $table->timestamp('mobile_verify_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
