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
            $table->string('slug')->unique()->nullable();
            $table->string('plate_number', 50);
            $table->decimal('current_price', 10, 2);
            $table->decimal('previous_price', 10, 2)->nullable();
            $table->integer('year');
            $table->integer('mileage');
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(1);
            $table->boolean('is_active')->default(1);
            $table->integer('engine_volume');
            $table->integer('door'); 
            $table->integer('cylinder'); 
            $table->integer('engine_power');
            $table->string('odometer_reading', 191)->nullable();
            $table->boolean('water_flood_damaged')->default(false);
            $table->boolean('former_rental_car')->default(false);     
            $table->boolean('former_taxi')->default(false);          
            $table->boolean('recovered_theft')->default(false);      
            $table->boolean('police_car')->default(false);            
            $table->boolean('salvage_record')->default(false);     
            $table->boolean('fuel_conversion')->default(false);      
            $table->boolean('modified_seats')->default(false); 
            $table->date('first_registered_date');
            $table->foreignId('category_id');
            $table->foreignId('condition_id');
            $table->foreignId('brand_id');
            $table->foreignId('model_id');
            $table->foreignId('fuel_type_id');
            $table->foreignId('transmission_type_id');
            $table->foreignId('color_id');
            $table->foreignId('drive_type_id');
            $table->foreignId('steering_id');
            $table->foreignId('passenger_id');
            $table->string('size', 191);
            $table->enum('status', ['available', 'booked', 'sold'])->default('available');
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
