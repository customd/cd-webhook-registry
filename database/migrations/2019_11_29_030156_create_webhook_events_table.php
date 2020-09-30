<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookEventsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event');
            $table->foreignId('webhook_endpoint_id')->constrained('webhook_endpoints')->onDelete('cascade')->onUpdate('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('webhook_events');
    }
}
