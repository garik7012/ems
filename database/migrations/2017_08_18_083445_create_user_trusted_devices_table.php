<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTrustedDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trusted_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enterprise_id');
            $table->integer('user_id');
            $table->string('token');
            $table->timestamp('created_at');
            $table->timestamp('expire_end_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_trusted_devices');
    }
}
