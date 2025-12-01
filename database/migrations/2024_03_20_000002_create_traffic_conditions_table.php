<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('traffic_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('level')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traffic_conditions');
    }
}; 