<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index(): View
    {
        $categories = Category::with('menuItems')->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan form edit kategori
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Mengupdate kategori
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus kategori
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 