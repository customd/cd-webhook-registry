<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookConsumersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('webhook_consumers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('token');
            $table->string('base_url');
            $table->smallInteger('verify_ssl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('webhook_consumers');
    }
}
