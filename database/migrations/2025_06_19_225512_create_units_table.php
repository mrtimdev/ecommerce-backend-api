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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Full name of the unit (e.g., Kilogram, Liter)');
            $table->string('abbreviation')->nullable()->comment('Short form of the unit (e.g., kg, L)');
            $table->string('type')->nullable()->comment('Type of unit: weight, volume, quantity, etc.');
            $table->boolean('is_active')->default(true)->comment('Whether the unit is active for use');
            $table->integer('sort_order')->default(0)->comment('For ordering units in dropdowns');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('is_active');
        });

        // Optional: Seed some basic units
        DB::table('units')->insert([
            [
                'name' => 'Kilogram',
                'abbreviation' => 'kg',
                'type' => 'weight',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
                'type' => 'weight',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'L',
                'type' => 'volume',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Milliliter',
                'abbreviation' => 'ml',
                'type' => 'volume',
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Piece',
                'abbreviation' => 'pc',
                'type' => 'quantity',
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'box',
                'type' => 'quantity',
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pack',
                'abbreviation' => 'pack',
                'type' => 'quantity',
                'is_active' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
