<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->nullable(); // Stock for this specific size (optional)
            $table->decimal('price_adjustment', 10, 2)->default(0); // Extra cost for this size (e.g., XL costs +100)
            $table->timestamps();
            
            // Prevent duplicate combinations
            $table->unique(['product_id', 'size_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size');
    }
};