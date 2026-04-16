<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales;
use App\Models\Salesitem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Variant;
use App\Models\Stock;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Dompdf\Dompdf;
use Dompdf\Options;

class SalesController extends Controller
{
    // Menampilkan semua transaksi penjualan hanya untuk cabang login
    public function index()
    {
        $branchId = Auth::user()->branch_id ?? 0;
        $userId = Auth::id(); // Dapatkan ID user yang sedang login
        if (Auth::user()->role == 'Cashier') {
        $sales = Sales::with(
            'items.variant.product',
            'customer',
            'payment',
            'user',
            'branch'
        )
        ->orderBy('sales_id', 'desc')
        ->where('branch_id', $branchId)
        ->where('user_id', $userId)
        ->get();
        } else {
        $sales = Sales::with(
            'items.variant.product',
            'customer',
            'payment',
            'user',
            'branch'
        )
        ->orderBy('sales_id', 'desc')
        ->get();
        }


        return view('sales.index', compact('sales'));
    }

    // Form transaksi penjualan
    public function create()
    {
        $customers = Customer::all();
        $provinces = \Laravolt\Indonesia\Models\Province::pluck('name', 'code');
        $branchId = Auth::user()->branch_id ?? 0;
        // Ambil variant dengan stok > 0 di cabang aktif
        $variants = Variant::with([
            'product',
            'stocks' => function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            }
        ])
        ->whereHas('stocks', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId)
                ->where('stock', '>', 0);
        })
        ->get();


        return view('sales.create', compact('customers', 'variants', 'branchId', 'provinces'));
    }

    // Simpan transaksi baru
  public function store(Request $request)
{
    $branchId = Auth::user()->branch_id ?? 0;

    $request->validate([
        'customer_id' => 'nullable|exists:customers,customer_id',
        'payment_method' => 'required|string',
        'products' => 'required|array',
        'products.*.variant_id' => 'required|exists:variants,variant_id',
        'products.*.qty' => 'required|integer|min:1',
        'payment' => 'required|numeric|min:0',
        'qris_data' => 'nullable|string',
        'qris_code' => 'nullable|string'
    ]);

    $totalAmount = 0;
    $products = [];

    foreach ($request->products as $productData) {
        $variant = Variant::find($productData['variant_id']);

        // Validasi stok per cabang
        $stock = Stock::where('variant_id', $variant->variant_id)
                    ->where('branch_id', $branchId)
                    ->first();

        if (!$stock || $productData['qty'] > $stock->stock) {
            return back()->with('error', "Stok produk {$variant->product->product_name} hanya tersisa " . ($stock->stock ?? 0));
        }

        $totalAmount += $variant->product_sale * $productData['qty'];

        $products[] = [
            'variant_id' => $variant->variant_id,
            'quantity'   => $productData['qty'],
            'price'      => $variant->product_sale,
            'total'      => $variant->product_sale * $productData['qty'],
            'stock'      => $stock,
        ];
    }

    $totalAfterDiscount = $totalAmount - ($request->discount ?? 0);

    // Simpan transaksi dengan data QRIS jika metode pembayaran QRIS
    $salesData = [
        'customer_id'    => $request->customer_id,
        'user_id'        => Auth::id(),
        'branch_id'      => $branchId,
        'total_amount'   => $totalAfterDiscount,
        'discount'       => $request->discount ?? 0,
        'status'         => "completed"
    ];

    // Jika pembayaran QRIS, simpan data QRIS
    if ($request->payment_method === 'QRIS') {
        $salesData['qris_data'] = $request->qris_data;
        $salesData['qris_code'] = $request->qris_code;
    }

    $sales = Sales::create($salesData);

    // Simpan item penjualan & kurangi stok
    foreach ($products as $product) {
        Salesitem::create([
            'sales_id'  => $sales->sales_id,
            'variant_id'=> $product['variant_id'],
            'quantity'  => $product['quantity'],
            'price'     => $product['price'],
            'total'     => $product['total'],
        ]);

        $product['stock']->stock -= $product['quantity'];
        $product['stock']->save();
    }

    // Simpan pembayaran
    Payment::create([
        'sales_id'       => $sales->sales_id,
        'payment_method' => $request->payment_method,
        'amount'         => $request->payment,
        'change'         => $request->change ?? 0,
        'payment_date'   => today(),
        'user_id'        => Auth::id(),
    ]);

    return redirect()->route('sales.index', $sales->sales_id)
                 ->with('success', 'Transaksi berhasil disimpan.');
}
    // Detail transaksi
    public function show($id)
    {
        $branchId = Auth::user()->branch_id ?? 0;

        $sales = Sales::with(
            'items.variant.product',
            'customer',
            'payment',
            'user',
            'branch'
        )->findOrFail($id);

        // Pastikan user tidak bisa lihat transaksi cabang lain
        if ($sales->branch_id != $branchId) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('sales.show', compact('sales', 'branchId'));
    }

    public function generatePDFBySalesId($id)
    {
        $sales = Sales::with(
            'items.variant.product',
            'customer',
            'payment',
            'user',
            'branch' // tambahkan ini
        )->findOrFail($id);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Courier');

        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, 226.77, 800], 'portrait'); // ukuran struk thermal

        $html = view('sales.salespdf', compact('sales'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // stream langsung ke browser
        return $dompdf->stream("Struk_Penjualan_{$id}.pdf", ["Attachment" => false]);
    }
    // Method untuk mencari produk berdasarkan barcode
    public function findProductByBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $branchId = Auth::user()->branch_id ?? 0;
        $barcode = $request->barcode;

        // Cari variant berdasarkan barcode
        $variant = Variant::with(['product', 'stocks' => function($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        }])->where('barcode', $barcode)->first();

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Produk dengan barcode tersebut tidak ditemukan'
            ]);
        }

        // Cek stok
        $stock = $variant->stocks->first();
        $availableStock = $stock ? $stock->stock : 0;

        if ($availableStock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk ini habis'
            ]);
        }

        return response()->json([
            'success' => true,
            'variant' => [
                'variant_id' => $variant->variant_id,
                'product_name' => $variant->product->product_name,
                'warna' => $variant->warna,
                'ukuran' => $variant->ukuran,
                'barcode' => $variant->barcode,
                'product_sale' => $variant->product_sale,
                'stock' => $availableStock
            ]
        ]);
    }
}
