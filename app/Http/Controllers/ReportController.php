<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Branch;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Tampil halaman awal (default bulan ini)
    public function index()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::now()->endOfMonth()->format('Y-m-d');

        $branches = Branch::all();

        // Default sales: jika user role Cabang -> tampilkan cabang sendiri, else semua (untuk tampilan awal)
        $SalesID = Auth::user()->branch_id ?? null;
        if (Auth::user()->role === 'Cabang' && $SalesID) {
            $sales = Sales::with(['customer','user','payment','items.variant.product'])
                ->where('branch_id', $SalesID)
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
                ->orderByDesc('sales_id')
                ->get();
        } else {
            $sales = Sales::with(['customer','user','payment','items.variant.product'])
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
                ->orderByDesc('sales_id')
                ->get();
        }
        
        $grandTotal = $sales->sum('total_amount');


        return view('report.index', compact('sales','startDate','endDate','branches','SalesID','grandTotal'));
    }

    // Filter dari form (GET)
    public function filter(Request $request)
    {
        // parsing date_range (format: YYYY-MM-DD - YYYY-MM-DD)
        $dateRange = $request->input('date_range', null);

        if (!$dateRange) {
            // Jika tidak ada date_range, fallback ke bulan ini
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate   = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::parse($dates[0])->format('Y-m-d');
            $endDate   = Carbon::parse($dates[1])->format('Y-m-d');
        }

        $branchId = $request->input('branch_id', 'all');

        $query = Sales::with(['customer','user','payment','items.variant.product'])
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);

        // Jika role Cabang -> wajib filter branch user
        if (Auth::user()->role === 'Cabang') {
            $userBranch = Auth::user()->branch_id ?? null;
            if ($userBranch) {
                $query->where('branch_id', $userBranch);
                $branchId = $userBranch; // supaya selected di view
            }
        } else {
            // Jika admin dan memilih cabang spesifik
            if ($branchId && $branchId !== 'all') {
                $query->where('branch_id', $branchId);
            }
        }

        $sales = $query->orderByDesc('sales_id')->get();
        $branches = Branch::all();
        $SalesID = Auth::user()->branch_id ?? null;

        // Kirim juga date_range agar inputnya terisi ulang di view
        $date_range = $startDate . ' - ' . $endDate;

        return view('report.index', compact('sales','startDate','endDate','branches','SalesID','date_range','branchId'));
    }

    // Generate PDF (POST) — menerima start_date, end_date, branch_id
    public function generatePDF(Request $request)
    {
        $start = $request->input('start_date');
        $end   = $request->input('end_date');

        // Validasi minimal
        if (!$start || !$end) {
            // fallback: bulan ini
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end   = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $startDate = $start . ' 00:00:00';
        $endDate   = $end . ' 23:59:59';

        $branchId = $request->input('branch_id', 'all');

        $query = Sales::with(['customer','user','payment','items.variant.product'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        // Jika user role Cabang, override branch agar tidak bisa mencetak cabang lain
        if (Auth::user()->role === 'Cabang') {
            $userBranch = Auth::user()->branch_id ?? null;
            if ($userBranch) $query->where('branch_id', $userBranch);
        }

        $sales = $query->orderByDesc('sales_id')->get();

        $tanggalBulanTahunawal = date("d-m-Y", strtotime($startDate));
        $tanggalBulanTahunakhir = date("d-m-Y", strtotime($endDate));

        // generate pdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');

        $html = view('report.laporanpdf', compact('sales','tanggalBulanTahunawal','tanggalBulanTahunakhir'))->render();

        $dompdf->loadHtml($html);
        $dompdf->render();

        $fileName = "Laporan_Penjualan_{$tanggalBulanTahunawal}_{$tanggalBulanTahunakhir}.pdf";
        return $dompdf->stream($fileName);
    }
}
