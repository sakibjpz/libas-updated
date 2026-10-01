<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class MigrateProductCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:migrate-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate product category names to category IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting category migration...');
        
        // Get all unique category names from products
        $categoryNames = Product::whereNotNull('category')
                               ->where('category', '!=', '')
                               ->distinct()
                               ->pluck('category')
                               ->toArray();
        
        $this->info("Found " . count($categoryNames) . " unique category names in products.");
        
        // First, create missing categories
        $createdCategories = 0;
        foreach ($categoryNames as $categoryName) {
            // Check if category exists
            $category = Category::where('name', $categoryName)->first();
            
            if (!$category) {
                // Create category
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => $this->createSlug($categoryName)
                ]);
                $createdCategories++;
                $this->line("Created category: '{$categoryName}'");
            }
        }
        
        $this->info("Created {$createdCategories} new categories.");
        
        // Now migrate products
        $products = Product::whereNotNull('category')
                          ->where('category', '!=', '')
                          ->whereNull('category_id')
                          ->get();
        
        $this->info("Migrating {$products->count()} products...");
        
        $migrated = 0;
        $failed = 0;
        
        foreach ($products as $product) {
            // Find category by name
            $category = Category::where('name', $product->category)->first();
            
            if ($category) {
                $product->category_id = $category->id;
                $product->save();
                $migrated++;
                $this->line("Migrated: Product '{$product->name}' -> Category '{$category->name}'");
            } else {
                $failed++;
                $this->error("Category not found for: '{$product->category}'");
            }
        }
        
        $this->info("Migration completed!");
        $this->info("Successfully migrated: {$migrated} products");
        
        if ($failed > 0) {
            $this->warn("Failed to migrate: {$failed} products");
        }
        
        return 0;
    }
    
    /**
     * Create a slug from category name
     */
    private function createSlug($name)
    {
        // Simple slug creation
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}