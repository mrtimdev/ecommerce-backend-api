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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade'); // Link to the stock entry
            $table->decimal('amount', 10, 2); // Amount of this specific payment
            $table->date('payment_date'); // Date this payment was made
            $table->string('payment_method')->nullable(); // e.g., cash, bank transfer, card
            $table->text('note')->nullable(); // Any specific notes for this payment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
