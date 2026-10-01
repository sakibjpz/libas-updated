<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NavigationMenuController extends Controller
{
    /**
     * List top-level menus with their submenus.
     */
    public function index()
    {
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        return view('admin.menu-items.index', compact('menus'));
    }

    public function create()
    {
        $parents = $this->parentOptions();
        $categories = $this->categoryOptions();
        return view('admin.menu-items.create', compact('parents', 'categories'));
    }

    public function store(Request $request)
    {
        Menu::create($this->validated($request));

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parents = $this->parentOptions($menu->id);
        $categories = $this->categoryOptions();
        return view('admin.menu-items.edit', compact('menu', 'parents', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($this->validated($request, $menu->id));

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        // Deleting a parent also removes its submenus
        Menu::where('parent_id', $menu->id)->delete();
        $menu->delete();

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu deleted successfully.');
    }

    /**
     * Top-level menus that can be chosen as a parent (max depth: 2 levels).
     */
    private function parentOptions(?int $excludeId = null)
    {
        return Menu::whereNull('parent_id')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('title')
            ->get();
    }

    private function categoryOptions()
    {
        return \App\Models\Category::orderBy('name')->get();
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => [
                'nullable', 'string', 'max:255',
                Rule::unique('menus', 'slug')->ignore($ignoreId),
            ],
            'url'       => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => [
                'nullable',
                Rule::exists('menus', 'id')->where(fn ($q) => $q->whereNull('parent_id')),
            ],
            'order'     => 'nullable|integer|min:0',
            'status'    => 'required|in:active,inactive',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['parent_id'] = $data['parent_id'] ?: null;
        $data['category_id'] = $data['category_id'] ?: null;
        $data['order'] = $data['order'] ?? 0;

        return $data;
    }
}
