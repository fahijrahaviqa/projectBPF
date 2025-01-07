<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index(): View
    {
        $categories = Category::withCount('menuItems')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:255',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit kategori
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Mengupdate kategori
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Menghapus kategori
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menuItems()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki menu');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
} 