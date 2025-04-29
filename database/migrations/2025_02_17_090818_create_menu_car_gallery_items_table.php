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
        Schema::create('menu_car_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('pdf_path');
            $table->tinyInteger('is_active');
            $table->foreignId('menu_car_gallery_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_car_gallery_items');
    }
};
