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
        Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained();
        $table->foreignId('product_id')->constrained();
        $table->integer('quantity');
        $table->decimal('price', 10, 2);
        $table->decimal('discount', 5, 2)->default(0);
        $table->string('category');
        $table->string('unit');
        $table->string('payment_status');
        $table->date('date');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
