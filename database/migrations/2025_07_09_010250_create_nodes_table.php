<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodesTable extends Migration
{
    public function up()
{
    Schema::create('nodes', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('ip');
        $table->enum('status', ['online', 'offline', 'partial']);
        $table->decimal('latitude', 10, 6);
        $table->decimal('longitude', 10, 6);
        $table->string('uptime')->nullable();
        $table->string('last_ping')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
