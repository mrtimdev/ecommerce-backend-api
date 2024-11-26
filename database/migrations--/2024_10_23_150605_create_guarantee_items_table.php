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
        Schema::create('guarantee_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('description')->nullable();
            $table->json('port_check')->nullable();
            $table->foreignId('guarantee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantee_items');
    }
};
