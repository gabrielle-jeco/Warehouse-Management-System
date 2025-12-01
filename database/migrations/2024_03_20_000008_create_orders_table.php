<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('agent_id')->constrained();
            $table->foreignId('store_location_id')->constrained('locations');
            $table->foreignId('drop_location_id')->constrained('locations');
            $table->date('order_date');
            $table->time('order_time');
            $table->time('pickup_time');
            $table->foreignId('weather_id')->constrained('weather_conditions');
            $table->foreignId('traffic_id')->constrained('traffic_conditions');
            $table->integer('delivery_time');
            $table->foreignId('category_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 