<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as StockRequest;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // Menampilkan semua permintaan stok
    public function index()
    {
        $RequestId = Auth::user()->branch_id ?? 0;
        
        if (Auth::user()->role == 'Cabang') {
            $dataRequest = StockRequest::with(['variant', 'branch'])
                ->where('branch_id', $RequestId)
                ->get();
        } else {
            $dataRequest = StockRequest::with(['variant', 'branch'])->get();
        }
        
        return view('request.index', compact('dataRequest', 'RequestId'));
    }

    // Form pengajuan stok
    public function create()
    {
        $branches = Auth::user()->branch_id ?? 0;
        $databranch = Branch::find($branches); 
        $Var = Stock::where('branch_id', $branches)
            ->pluck('variant_id')
            ->toArray();

        $variants = Variant::with('product')
        ->whereHas('product')
        ->whereIn('variant_id', $Var)
        ->get();
        return view('request.create', compact('branches', 'variants', 'databranch'));
    }

    // Simpan permintaan stok baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:variants,variant_id',
            'branch_id' => 'required|exists:branch,branch_id',
            'stock' => 'required|integer|min:1',
        ]);

        $currentStock = Stock::where('variant_id', $validated['variant_id'])
            ->where('branch_id', $validated['branch_id'])
            ->value('stock'); // ambil nilai stok saja

if ($currentStock !== null && $currentStock > 100) {
    return redirect()->back()
        ->withInput()
        ->with('error', 'Stok produk ini masih di atas 100, tidak dapat mengajukan permintaan baru.');
}

        StockRequest::create([
            'variant_id' => $validated['variant_id'],
            'branch_id' => $validated['branch_id'],
            'stock' => $validated['stock'],
            'status' => 'Pending',
            'pengiriman' => 'Proses',  // Pengiriman diatur ke 'Proses' saat pertama kali
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Permintaan stok berhasil dikirim ke admin pusat.');
    }

    // Update status admin pusat (Terima/Tolak)
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Ditolak',
        ]);

        $req = StockRequest::findOrFail($id);
        $req->status = $validated['status'];

        // Pastikan pengiriman tidak null
        if ($req->status === 'Diterima' && is_null($req->pengiriman)) {
            $req->pengiriman = 'Proses';  // Jika diterima dan pengiriman masih null, atur ke 'Proses'
        }

        $req->save();

        return back()->with('success', 'Status permintaan berhasil diperbarui.');
    }

    // Admin pusat kirim stok
    public function kirim($id)
    {
        $req = StockRequest::findOrFail($id);

        if ($req->status !== 'Diterima' || $req->pengiriman === 'Dikirim') {
            return back()->with('error', 'Permintaan belum siap dikirim.');
        }

        $req->pengiriman = 'Dikirim';
        $req->save();

        return back()->with('success', 'Barang sedang dikirim ke cabang.');
    }

    // Admin pusat terima stok
    public function diterima($id)
    {
        $req = StockRequest::findOrFail($id);

        if ($req->status !== 'Diterima' || $req->pengiriman !== 'Dikirim') {
            return back()->with('error', 'Barang belum dikirim atau belum diterima oleh admin pusat.');
        }

        $req->pengiriman = 'Diterima';
        $req->save();

        // Tambah stok cabang
        $stock = Stock::firstOrCreate(
            ['variant_id' => $req->variant_id, 'branch_id' => $req->branch_id],
            ['stock' => 0]
        );

        $stock->stock += $req->stock;
        $stock->save();

        return back()->with('success', 'Barang diterima dan stok berhasil ditambahkan.');
    }
}
