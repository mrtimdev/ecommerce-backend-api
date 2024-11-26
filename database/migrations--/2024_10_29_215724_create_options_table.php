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
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->unique();
            $table->unsignedBigInteger('option_group_id');
        });
        Schema::create('option_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->unique();
            $table->unsignedBigInteger('option_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
        Schema::dropIfExists('option_items');
    }
};
