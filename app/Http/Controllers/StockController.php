<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Variant;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index()
    {

        $branchId = Auth::user()->branch_id ?? 0;
       
        if (Auth::user()->role == 'Cabang') {
            // User cabang hanya lihat stok cabangnya
            $dataStocks = Stock::with(['variant', 'branch'])
                ->where('branch_id', $branchId)
                ->get();
        
        } else {
            // Admin atau role lain bisa filter stok berdasarkan cabang
            $dataStocks = Stock::with(['variant', 'branch'])->get();
        }

        return view('stocks.index', compact('dataStocks', 'branchId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dataVariants = Variant::all();
        $dataBranches = Branch::all();

        return view('stocks.create', compact('dataVariants', 'dataBranches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stock' => 'required|numeric',
            'variant_id' => 'required|exists:variants,variant_id',
            'branch_id' => 'required|exists:branch,branch_id',
        ]);

        $existingStock = Stock::where('variant_id', $request->variant_id)
            ->where('branch_id', $request->branch_id)
            ->first();

        if ($existingStock) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok untuk produk ini di cabang tersebut sudah ada!');
        }
        //dd($existingStock);
        Stock::create([
            'stock' => $request->stock,
            'variant_id' => $request->variant_id,
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Tidak ada stok duplikat, data bisa disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataEditStock = Stock::findOrFail($id);
        $variants = Variant::all();
        $branches = Branch::all();

        return view('stocks.edit', compact('dataEditStock', 'variants', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stock' => 'required|numeric',
            'variant_id' => 'required|exists:variants,variant_id',
            'branch_id' => 'required|exists:branch,branch_id',
        ]);

        $stock = Stock::findOrFail($id);

        $stock->update([
            'stock' => $request->stock,
            'variant_id' => $request->variant_id,
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stock = Stock::findOrFail($id);

        if ($stock->stock > 0) {
            return redirect()->route('stocks.index')->with('error', 'Stok masih tersedia, tidak dapat dihapus!');
        }
        
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock berhasil dihapus!');
    }
}
