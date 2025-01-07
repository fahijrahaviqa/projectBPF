<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu untuk halaman publik
     */
    public function index(): View
    {
        try {
            // Ambil kategori dengan eager loading menu items
            $categories = Category::with('menuItems')->get();
            
            // Ambil semua menu dengan eager loading kategori
            $menuItems = MenuItem::with('categories')->get();
            
            // Filter menu berdasarkan kategori
            $menuMakanan = $menuItems->filter(function ($item) {
                return $item->categories->contains('name', 'Makanan');
            })->values(); // Tambahkan values() untuk reset index array
            
            $menuMinuman = $menuItems->filter(function ($item) {
                return $item->categories->contains('name', 'Minuman');
            })->values(); // Tambahkan values() untuk reset index array

            return view('menu', compact('menuMakanan', 'menuMinuman'));
            
        } catch (\Exception $e) {
            // Log error dan tampilkan pesan error yang friendly
            \Log::error('Error in MenuController@index: ' . $e->getMessage());
            return view('menu')->with('error', 'Terjadi kesalahan saat memuat menu.');
        }
    }

    /**
     * Menampilkan daftar menu untuk halaman admin
     */
    public function adminIndex(): View
    {
        $menuItems = MenuItem::with('categories')
            ->latest()
            ->paginate(10);

        return view('admin.menu.index', compact('menuItems'));
    }

    /**
     * Menampilkan form tambah menu
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Menampilkan form edit menu
     */
    public function edit(MenuItem $menuItem): View
    {
        $categories = Category::all();
        $menuItem->load('categories');
        
        return view('admin.menu.edit', compact('menuItem', 'categories'));
    }

    /**
     * Menampilkan detail menu
     */
    public function show(MenuItem $menuItem): View
    {
        $menuItem->load('categories');
        return view('admin.menu.show', compact('menuItem'));
    }
}
