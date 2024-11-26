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
        Schema::create('community_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link')->nullable();
            $table->foreignId('community_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_items');
    }
};
