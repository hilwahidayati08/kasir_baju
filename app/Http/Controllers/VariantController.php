<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VariantController extends Controller
{
    public function index()
    {
        $dataVariant = Variant::all();
        return view('variant.index', compact('dataVariant'));
    }

    public function create()
    {
        $dataProduct = Product::all();
        return view('variant.create', compact('dataProduct'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'warna' => 'required',
            'ukuran' => 'nullable',
            'product_price' => 'required|numeric',
            'product_sale' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);


        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('product-photos', 'public');
        }

        // GENERATE BARCODE OTOMATIS (unik)
        $barcode = rand(100000000000, 999999999999);
        Variant::create([
            'product_id' => $request->product_id,
            'warna' => $request->warna,
            'ukuran' => $request->ukuran,
            'product_price' => $request->product_price,
            'product_sale' => $request->product_sale,
            'photo' => $photoPath,
            'barcode' => $barcode,
        ]);

        return redirect()->route('variant.index')
            ->with('success', 'Variant berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $dataEditvariant = Variant::findOrFail($id);
        $products = Product::all();
        
        return view('variant.edit', compact('dataEditvariant', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'warna' => 'required',
            'ukuran' => 'required',
            'product_price' => 'required|numeric',
            'product_sale' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $variant = Variant::findOrFail($id);

        $photoPath = $variant->photo;

        if ($request->hasFile('photo')) {
            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }

            $photoPath = $request->file('photo')->store('product-photos', 'public');
        }

        $variant->update([
            'product_id' => $request->product_id,
            'warna' => $request->warna,
            'ukuran' => $request->ukuran,
            'product_price' => $request->product_price,
            'product_sale' => $request->product_sale,
            'photo' => $photoPath,
        ]);

        return redirect()->route('variant.index')->with('success', 'Variant berhasil diubah!');
    }

    public function destroy(string $id)
    {
        Variant::where('variant_id', $id)->delete();
        return redirect(route('variant.index'))
            ->with('success', 'Variant berhasil dihapus!');
    }

 public function printBarcode($id)
{
    $variant = Variant::findOrFail($id);
    $jumlah = $request->jumlah ?? 1;

    return view('variant.print', compact('variant'));
}

}
