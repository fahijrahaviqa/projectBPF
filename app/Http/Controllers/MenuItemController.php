<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use App\Http\Requests\MenuItem\StoreMenuItemRequest;
use App\Http\Requests\MenuItem\UpdateMenuItemRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    /**
     * Menampilkan daftar menu
     */
    public function index(): View
    {
        $menuItems = MenuItem::with('categories')->get();
        $categories = Category::all();
        
        return view('menu.index', compact('menuItems', 'categories'));
    }

    /**
     * Menampilkan form tambah menu
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('menu.create', compact('categories'));
    }

    /**
     * Menyimpan menu baru
     */
    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Handle upload gambar jika ada
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-images', 'public');
                $validated['image'] = $imagePath;
            }

            $menuItem = MenuItem::create($validated);
            
            // Attach categories
            $menuItem->categories()->attach($validated['category_ids']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil ditambahkan',
                'data' => $menuItem->load('categories')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan form edit menu
     */
    public function edit(MenuItem $menuItem): View
    {
        $categories = Category::all();
        $menuItem->load('categories');
        
        return view('menu.edit', compact('menuItem', 'categories'));
    }

    /**
     * Mengupdate menu
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Handle upload gambar baru jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($menuItem->image) {
                    Storage::disk('public')->delete($menuItem->image);
                }
                
                $imagePath = $request->file('image')->store('menu-images', 'public');
                $validated['image'] = $imagePath;
            }

            $menuItem->update($validated);
            
            // Sync categories
            $menuItem->categories()->sync($validated['category_ids']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil diperbarui',
                'data' => $menuItem->load('categories')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus menu
     */
    public function destroy(MenuItem $menuItem): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Hapus gambar jika ada
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }

            // Hapus menu dan relasinya
            $menuItem->categories()->detach();
            $menuItem->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 