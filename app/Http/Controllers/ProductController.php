<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $dataProducts = Product::all();
        return view('products.index', data: compact('dataProducts'));
    }

    public function create()
    {
        $dataCategory = Category::all();
        return view('products.create', compact('dataCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'product_description' => 'required',
            'category_id' => 'required',
        ]);
        
        Product::create([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'category_id' => $request->category_id,

        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $dataEditproduct = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('dataEditproduct', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_name' => 'required',
            'product_description' => 'required',
            'category_id' => 'required',
        ]);

        $dataUpdateproduct = Product::findOrFail($id);

        $dataUpdateproduct->update([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui dan stok ditambahkan!');
    }


    public function destroy(string $id)
    {
        Product::where('product_id', $id)->delete();
        return redirect(route('products.index'));
    }
}
