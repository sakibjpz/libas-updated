<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductLandingController extends Controller
{
    /**
     * Available landing page themes.
     * Add a new entry + a matching view at
     * resources/views/landing/themes/{key}.blade.php to register a theme.
     */
    public const THEMES = [
        'gold'     => 'Royal Gold',
        'midnight' => 'Midnight Luxe',
        'emerald'  => 'Emerald Fresh',
        'rose'     => 'Rose Charm',
        'sapphire' => 'Sapphire Blue',
        'crimson'  => 'Crimson Wine',
        'violet'   => 'Violet Luxe',
        'ocean'    => 'Ocean Teal',
        'sunset'   => 'Sunset Amber',
        'noir'     => 'Noir Minimal',
    ];

    /**
     * Standalone product landing page (/lp/{slug}) using the product's chosen theme.
     */
    public function show(Product $product)
    {
        $product->load('categoryRelation', 'sizes', 'colors');

        $theme = $product->landing_theme ?: 'gold';
        if (!array_key_exists($theme, self::THEMES)) {
            $theme = 'gold';
        }

        // Server-side ViewContent (Meta CAPI), deduped with browser pixel
        $pixelEventId = \App\Services\MetaConversionsApi::eventId('vc');
        \App\Services\MetaConversionsApi::send('ViewContent', [
            'content_name' => $product->name,
            'content_category' => $product->category_name ?: 'General',
            'content_ids' => [(string) $product->id],
            'content_type' => 'product',
            'value' => $product->price,
            'currency' => 'BDT',
        ], [], $pixelEventId);

        return view("landing.themes.{$theme}", [
            'product' => $product,
            'theme'   => $theme,
            'pixelEventId' => $pixelEventId,
        ]);
    }
}
