<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('enterprise_id');
            $table->integer('user_id')->nullable();
            $table->smallInteger('is_ok');
            $table->string('login');
            $table->ipAddress('ip');
            $table->text('user_agent');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_stats');
    }
}
