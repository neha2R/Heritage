<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToChallangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challanges', function (Blueprint $table) {
            $table->enum('status',['0','1','2'])->default('0')->comment('0=> for send inviation, 1=> for accept invitation  2=> Reject invitation');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challanges', function (Blueprint $table) {
            //
        });
    }
}
