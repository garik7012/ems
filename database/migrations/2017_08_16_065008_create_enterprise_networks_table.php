<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnterpriseNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enterprise_id');
            $table->ipAddress('ristrict_network_ip');
            $table->string('description')->nullable();
            $table->smallInteger('is_active')->default(1);
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
        Schema::dropIfExists('enterprise_networks');
    }
}
