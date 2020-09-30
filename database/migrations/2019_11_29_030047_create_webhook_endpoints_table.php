<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('webhook_endpoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description')->nullable();
            $table->string('secret');
            $table->string('base_url');
            $table->smallInteger('verify_ssl')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('webhook_endpoints');
    }
}
