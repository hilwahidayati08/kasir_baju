<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data kategori
        $dataCategory = Category::all();
        return view('categories.index', compact('dataCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name'        => 'required|unique:categories,category_name',
        ]);

        Category::create([
            'category_name'        => $request->category_name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataEditcategory = Category::findOrFail($id);
        return view('categories.edit', compact('dataEditcategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // validasi unique tapi abaikan nama lama kategori dengan ID ini
            'category_name'        => 'required|unique:categories,category_name,' . $id . ',category_id',
        ]);

        $ubahCategory = Category::findOrFail($id);
        $ubahCategory->update([
            'category_name'        => $request->category_name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
