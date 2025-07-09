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
        Schema::create('ftd_stock_moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade'); // Link to the originating stock transaction
            $table->integer('quantity'); // Positive for 'in', negative for 'out'
            $table->foreignId('unit_id')->constrained('units'); // Assuming you have a units table
            $table->decimal('price', 10, 2); // Price per unit at the time of the move
            $table->timestamps(); // Records created_at (move_date) and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_moves');
    }
};
