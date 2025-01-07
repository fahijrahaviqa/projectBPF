<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(): View
    {
        $menuItems = MenuItem::with('categories')
            ->latest()
            ->paginate(10);

        return view('admin.menu.index', compact('menuItems'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $imagePath = $request->file('image')->store('menu-images', 'public');

            $menuItem = MenuItem::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'image' => $imagePath
            ]);

            $menuItem->categories()->attach($validated['category_ids']);

            return redirect()
                ->route('admin.menu.index')
                ->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan menu');
        }
    }

    public function edit(MenuItem $menu): View
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, MenuItem $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($menu->image);
                $validated['image'] = $request->file('image')->store('menu-images', 'public');
            }

            $menu->update($validated);
            $menu->categories()->sync($validated['category_ids']);

            return redirect()
                ->route('admin.menu.index')
                ->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui menu');
        }
    }

    public function destroy(MenuItem $menu): RedirectResponse
    {
        try {
            Storage::disk('public')->delete($menu->image);
            $menu->categories()->detach();
            $menu->delete();

            return redirect()
                ->route('admin.menu.index')
                ->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus menu');
        }
    }
} 