<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Get all active menus with their hierarchy
     */
    public static function getMenuHierarchy()
    {
        // Get all active main menus (parent_id is null)
        $mainMenus = \App\Models\Menu::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->with('category')
            ->get();

        // For each main menu, load its active children
        foreach ($mainMenus as $mainMenu) {
            $mainMenu->load(['children' => function($query) {
                $query->where('status', 'active')
                      ->orderBy('order', 'asc')
                      ->with(['category', 'children' => function($subQuery) {
                          $subQuery->where('status', 'active')
                                   ->orderBy('order', 'asc')
                                   ->with('category');
                      }]);
            }]);
        }

        return $mainMenus;
    }
}