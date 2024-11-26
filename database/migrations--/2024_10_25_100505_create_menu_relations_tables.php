<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create menu_brand pivot table
        Schema::create('menu_brand', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('brand_id');
        });

        // Create menu_model pivot table
        Schema::create('menu_model', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('model_id');
        });

        // Create menu_category pivot table
        Schema::create('menu_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('category_id');
        });

        // Create menu_fuel_type pivot table
        Schema::create('menu_fuel_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('fuel_type_id');
        });

        Schema::create('menu_steering', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('steering_id');
        });

        // Create menu_location pivot table
        Schema::create('menu_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('location_id');
        });
        Schema::create('menu_drive_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('drive_type_id');
        });
        Schema::create('menu_passenger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('passenger_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_brand');
        Schema::dropIfExists('menu_model');
        Schema::dropIfExists('menu_category');
        Schema::dropIfExists('menu_fuel_type');
        Schema::dropIfExists('menu_location');
    }
};
