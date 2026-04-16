<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Customer;
use App\Models\Variant;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\Request as RequestStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

    public function admindashboard(Request $request)
    {
        $branches = Branch::all();

        // ==== Hitungan dasar ====
        $countvariant = Variant::count();
        $countproduct = Product::count();
        $countbranch = Branch::count();
        $countcategory = Category::count();
        $countcustomer = Customer::count();
        $countuser = Users::count();

        // ==== Data penjualan ====
        $counttotaltoday = Sales::whereDate('created_at', Carbon::today())->sum('total_amount');
        $counttotalmonth = Sales::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
        $counttotalyear = Sales::whereYear('created_at', Carbon::now()->year)->sum('total_amount');
        $counttotaltoday1 = Sales::whereDate('created_at', Carbon::today())->count();

        // ==== Jumlah permintaan stok hari ini ====
        $countrequest = RequestStock::whereDate('created_at', Carbon::today())->count();

        // ==== Data grafik penjualan bulanan (12 bulan penuh) ====
        $sales = Sales::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month'); // hasil: [1 => 1200000, 4 => 500000, ...]

        // buat 12 bulan default (isi 0 kalau tidak ada transaksi)
        $months = [];
        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->translatedFormat('F'); // nama bulan bahasa Indonesia
            $totals[] = $sales[$i] ?? 0;
        }

        // ==== Produk terlaris berdasarkan bulan yang dipilih ====
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $currentYear = Carbon::now()->year;

        $product = SalesItem::selectRaw('
        products.product_name AS product_name,
        variants.warna AS warna,
        SUM(sales_items.quantity) AS total_sold
    ')
            ->join('variants', 'sales_items.variant_id', '=', 'variants.variant_id')
            ->join('products', 'variants.product_id', '=', 'products.product_id') // join tambahan
            ->whereMonth('sales_items.created_at', $selectedMonth)
            ->whereYear('sales_items.created_at', $currentYear)
            ->groupBy('products.product_name', 'variants.warna')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $productNames = $product->pluck('product_name');
        $totalSold = $product->pluck('total_sold');


        // ==== Nama bulan ====
        $month = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // ==== Data request stok berdasarkan role ====
        $branchId = Auth::user()->branch_id ?? 0;
        $dataRequest = RequestStock::with(['variant', 'branch'])
            ->when(Auth::user()->role == 'Cabang', fn($q) => $q->where('branch_id', $branchId))
            ->where(function ($q) {
                $q->where('status', 'Pending')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'Diterima')
                            ->whereIn('pengiriman', ['Proses', 'Dikirim']);
                    });
            })
            ->get();

        return view('dashboard.admin', compact(
            'branches',
            'countproduct',
            'countcategory',
            'countcustomer',
            'counttotalmonth',
            'countvariant',
            'countrequest',
            'countuser',
            'months',
            'totals',
            'counttotaltoday',
            'productNames',
            'totalSold',
            'month',
            'selectedMonth',
            'counttotalyear',
            'countbranch',
            'dataRequest',
            'counttotaltoday1'
        ));
    }


    public function getTopProductsByBranch($branchId)
    {
        $query = SalesItem::selectRaw('
            products.product_name AS product_name,
            variants.warna AS warna,
            SUM(sales_items.quantity) AS total_sold
        ')
            ->join('variants', 'sales_items.variant_id', '=', 'variants.variant_id')
            ->join('products', 'variants.product_id', '=', 'products.product_id')
            ->join('sales', 'sales_items.sales_id', '=', 'sales.sales_id');

        // 🔹 Filter cabang (jika tidak pilih "all")
        if ($branchId != 'all') {
            $query->where('sales.branch_id', $branchId);
        }

        // 🔹 Group & urutkan berdasarkan total terjual
        $topProducts = $query->groupBy('products.product_name', 'variants.warna')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 🔹 Return data JSON ke frontend
        return response()->json($topProducts);
    }


    public function cashierdashboard()
    {
        $branchId = Auth::user()->branch_id ?? 0;
        $userId = Auth::id(); // Dapatkan ID user yang sedang login

        $todaySales = Sales::whereDate('created_at', Carbon::today())
            ->where('branch_id', $branchId)
            ->where('user_id', $userId)
            ->sum('total_amount');

        $totalTransactions = Sales::whereDate('created_at', Carbon::today())
            ->where('branch_id', $branchId)
            ->where('user_id', $userId)

            ->count();

        return view('dashboard.cashier', compact('todaySales', 'totalTransactions'));
    }

    public function branchdashboard(Request $request)
    {
        $branchId = Auth::user()->branch_id ?? 0;
        $currentYear = Carbon::now()->year;
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $counttotaltoday = Sales::where('branch_id', $branchId)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // ===== Top 5 Produk Terlaris Bulan Ini =====
        $productQuery = SalesItem::selectRaw('variants.warna as warna, SUM(sales_items.quantity) as total_sold')
            ->join('variants', 'sales_items.variant_id', '=', 'variants.variant_id')
            ->join('sales', 'sales_items.sales_id', '=', 'sales.sales_id')
            ->where('sales.branch_id', $branchId)
            ->whereMonth(\DB::raw("CONVERT_TZ(sales_items.created_at, '+00:00', '+07:00')"), $selectedMonth)
            ->whereYear('sales_items.created_at', $currentYear)
            ->groupBy('variants.warna')
            ->orderByDesc('total_sold')
            ->take(5);

        // ===== Data Grafik Penjualan Bulanan (12 bulan penuh) =====
        $sales = Sales::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->where('branch_id', $branchId)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Buat array 12 bulan (isi 0 kalau tidak ada transaksi)
        $months = [];
        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $totals[] = $sales[$i] ?? 0;
        }

        // ===== Data Tambahan =====
        $productNames = $productQuery->pluck('warna');
        $totalSold = $productQuery->pluck('total_sold');
        $totalVariants = Stock::where('branch_id', $branchId)
            ->distinct('variant_id')
            ->count('variant_id');
        $totalStocks = Stock::where('branch_id', $branchId)->sum('stock');
        $totalRequests = RequestStock::where('branch_id', $branchId)
            ->whereDate('created_at',)
            ->sum('request_id');
        $pendingRequests = RequestStock::where('status', 'pending')
            ->where('branch_id', $branchId)
            ->count();

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

        return view('dashboard.branch', compact(
            'productNames',
            'totalSold',
            'totalRequests',
            'pendingRequests',
            'totalVariants',
            'totalStocks',
            'months',
            'totals',
            'selectedMonth',
            'counttotaltoday',
            'dataStocks'
        ));
    }

}
