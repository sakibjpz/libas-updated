<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->integer('rating')->default(5);
            $table->text('review');
            $table->boolean('approved')->default(false);
            $table->timestamps();

            $table->index('product_id');
            $table->index('user_id');
            $table->index('approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
