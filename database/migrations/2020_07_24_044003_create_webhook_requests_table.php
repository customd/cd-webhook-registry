<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('webhook_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('httpVerb');
            $table->string('webhookUrl');
            $table->string('status');
            $table->string('payload')->nullable();
            $table->string('meta')->nullable();
            $table->string('responseBody')->nullable();
            $table->integer('attempt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('webhook_requests');
    }
}
