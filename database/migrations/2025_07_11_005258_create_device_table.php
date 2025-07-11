<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Gedung
            $table->string('ip')->unique(); // Alamat IP
            $table->enum('status', ['online', 'offline', 'partial'])->default('offline');
            $table->decimal('latitude', 10, 8);  // Misal: -8.17330000
            $table->decimal('longitude', 11, 8); // Misal: 112.68490000
            $table->timestamp('last_ping')->nullable(); // Waktu ping terakhir
            $table->decimal('uptime', 5, 2)->nullable(); // Uptime (misalnya 99.99%)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
