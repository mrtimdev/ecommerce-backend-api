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
        Schema::create('car_hot_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hot_mark_id');
            $table->foreignId('car_id');
        });
        Schema::create('car_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id');
            $table->foreignId('car_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_hot_marks');
        Schema::dropIfExists('car_options');
    }
};
