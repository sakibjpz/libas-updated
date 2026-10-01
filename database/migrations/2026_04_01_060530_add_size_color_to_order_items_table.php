<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('size_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->foreignId('color_id')->nullable()->after('size_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['size_id']);
            $table->dropForeign(['color_id']);
            $table->dropColumn(['size_id', 'color_id']);
        });
    }
};