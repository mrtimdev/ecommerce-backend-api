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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('plate_number', 50);
            $table->decimal('current_price', 10, 2);
            $table->decimal('previous_price', 10, 2)->nullable();
            $table->integer('year');
            $table->integer('mileage');
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(1);
            $table->boolean('is_active')->default(1);
            $table->foreignId('category_id')->constrained();
            $table->foreignId('condition_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('model_id')->constrained();
            $table->foreignId('fuel_type_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
