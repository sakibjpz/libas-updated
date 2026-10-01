<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('meta_title')->nullable()->after('description');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
        });

        // Backfill unique slugs for existing products
        $used = [];
        foreach (DB::table('products')->select('id', 'name')->orderBy('id')->get() as $product) {
            $base = Str::slug($product->name) ?: 'product';
            $slug = $base;
            $i = 2;
            while (isset($used[$slug])) {
                $slug = $base . '-' . $i++;
            }
            $used[$slug] = true;
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });
    }
};
