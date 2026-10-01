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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('fraud_flag')->default(false)->after('steadfast_sent_at');
            $table->integer('fraud_score')->nullable()->after('fraud_flag');
            $table->json('fraud_flags')->nullable()->after('fraud_score');
            $table->timestamp('fraud_checked_at')->nullable()->after('fraud_flags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fraud_flag', 'fraud_score', 'fraud_flags', 'fraud_checked_at']);
        });
    }
};
