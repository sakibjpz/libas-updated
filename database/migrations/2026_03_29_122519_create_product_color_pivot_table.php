<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->nullable(); // Stock for this specific color (optional)
            $table->decimal('price_adjustment', 10, 2)->default(0); // Extra cost for this color
            $table->timestamps();
            
            // Prevent duplicate combinations
            $table->unique(['product_id', 'color_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_color');
    }
};