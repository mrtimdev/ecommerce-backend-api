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
        // database/migrations/[timestamp]_create_deliveries_table.php
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->text('notes')->nullable();
            $table->date('delivery_date');
            $table->timestamps();
        });

        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
