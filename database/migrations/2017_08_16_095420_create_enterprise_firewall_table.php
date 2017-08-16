<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnterpriseFirewallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_firewall', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('enterprise_id');
            $table->ipAddress('ip_from');
            $table->ipAddress('ip_to');
            $table->string('action');
            $table->string('note')->nullable();
            $table->smallInteger('priority');
            $table->smallInteger('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterprise_firewall');
    }
}
