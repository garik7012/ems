<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('enterprise_id');
            $table->integer('user_id')->nullable();
            $table->string('from_email');
            $table->string('to_email');
            $table->string('subject');
            $table->text('data');
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
        Schema::dropIfExists('email_stats');
    }
}
