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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 50)->nullable();
            $table->string('telegram', 191)->nullable();
            $table->string('telegram_channel', 191)->nullable();
            $table->string('facebook_page', 191)->nullable();
            $table->string('youtube', 191)->nullable();
            $table->string('tiktok', 191)->nullable();
            $table->string('address', 191)->nullable();
            $table->string('image_path', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
