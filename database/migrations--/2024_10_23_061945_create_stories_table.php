<?php

use App\Models\Country;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->foreignIdFor(Country::class)->nullable()->constrained()->onDelete('cascade'); 
            $table->text('youtube_link')->nullable();
            $table->text('facebook_link')->nullable();
            $table->string('description')->nullable();
            $table->integer('view')->default(0);
            $table->integer('like')->default(0);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
