<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeoSeeder extends Seeder
{
    /**
     * Fill premium SEO meta fields (title, description, keywords) for products
     * that don't have them set yet. Safe to re-run — never overwrites hand-edited values.
     */
    public function run(): void
    {
        Product::with('categoryRelation')->chunk(100, function ($products) {
            foreach ($products as $product) {
                $audience = $this->audienceFor($product);
                $category = $product->category_name;

                $product->meta_title = $product->meta_title
                    ?: $this->makeTitle($product);
                $product->meta_description = $product->meta_description
                    ?: $this->makeDescription($product, $audience);
                $product->meta_keywords = $product->meta_keywords
                    ?: $this->makeKeywords($product, $audience, $category);

                $product->saveQuietly();
            }
        });
    }

    private function makeTitle(Product $product): string
    {
        $price = number_format((float) $product->price);
        return Str::limit($product->name, 48, '') . " - ৳{$price} | LibasBD";
    }

    private function makeDescription(Product $product, string $audience): string
    {
        $price = number_format((float) $product->price);
        $category = strtolower((string) $product->category_name) ?: 'fashion';
        $brand = $product->brand ? $product->brand . ' ' : '';

        $desc = "{$product->name} - premium {$brand}{$category} for {$audience} in Bangladesh. "
            . "100% authentic, cash on delivery, fast nationwide shipping. "
            . "Only ৳{$price} at LibasBD.";

        return Str::limit($desc, 160, '');
    }

    private function makeKeywords(Product $product, string $audience, ?string $category): string
    {
        $words = collect(explode(' ', preg_replace('/[^a-zA-Z0-9\s\-\']/', ' ', $product->name)))
            ->map(fn ($w) => trim($w))
            ->filter(fn ($w) => mb_strlen($w) > 2)
            ->take(8)
            ->implode(', ');

        $categoryKeywords = match (true) {
            str_contains(strtolower((string) $category), 'burkha') =>
                'abaya for women, burkha online bangladesh, modest fashion, stone work abaya, premium abaya bd',
            str_contains(strtolower((string) $category), 'cosmetic') =>
                'skincare bangladesh, original cosmetics bd, korean skincare, beauty products for men and women',
            str_contains(strtolower((string) $category), '3pcs'), str_contains(strtolower((string) $category), '3 piece') =>
                'pakistani 3 piece, unstitched 3 piece suit, luxury 3 piece for women, chiffon 3 piece, eid dress women',
            default => 'online shopping bangladesh, premium fashion',
        };

        $audienceKeywords = match ($audience) {
            'women' => "women's fashion, for women",
            'men' => "men's fashion, for men",
            'kids' => "kids fashion, for kids",
            default => "for men and women",
        };

        return "{$words}, {$categoryKeywords}, {$audienceKeywords}, buy online Bangladesh, LibasBD";
    }

    private function audienceFor(Product $product): string
    {
        $text = strtolower($product->name . ' ' . $product->category_name);

        return match (true) {
            str_contains($text, 'kid'), str_contains($text, 'baby'),
            str_contains($text, 'children') => 'kids',

            str_contains($text, 'burkha'), str_contains($text, 'abaya'),
            str_contains($text, '3 piece'), str_contains($text, '3pcs'),
            str_contains($text, 'women') => 'women',

            str_contains($text, 'men') && !str_contains($text, 'women') => 'men',

            default => 'men and women',
        };
    }
}
