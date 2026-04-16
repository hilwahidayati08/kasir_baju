@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- ============================
        TOP CARD SUMMARY
    ============================= --}}
    <div class="row">
        {{-- Varian --}}
        <div class="col-12 col-sm-3 col-md-4 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <i class="bx bx-package" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Varian</div>
                    </div>
                    <h3 class="mb-0" style="color: white;">{{ $totalVariants ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Stok --}}
        <div class="col-12 col-sm-3 col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <i class="bx bx-box" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Stok</div>
                    </div>
                    <h3 class="mb-0" style="color: white;">{{ $totalStocks ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Penjualan Hari Ini --}}
        <div class="col-12 col-sm-3 col-md-4 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bx bx-time-five" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Hari Ini</div>
                    </div>
                    <h3 class="mb-0" style="color: white;"">Rp. {{ number_format($counttotaltoday ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================
        CHARTS
    ============================= --}}
    <div class="row">
        {{-- Bar Chart Laporan Bulanan --}}
        <div class="col-md-8 col-sm-12 mb-4">
            <div class="card shadow-sm" style="height: 400px;">
                <div class="card-header"><h5 class="mb-0">Laporan Transaksi Bulanan</h5></div>
                <div class="card-body p-2">
                    <canvas id="myChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Chart Top 5 Produk --}}
        <div class="col-md-4 col-sm-12 mb-4">
            <div class="card shadow-sm" style="height: 400px;">
                <div class="card-header">
                    <h5 class="mb-0">Top 5 Produk Terlaris ({{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }})</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="topProductPieChart" style="max-width: 300px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================
        TABLE STOK
    ============================= --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Daftar Stok</h5>
                <div class="card-body">

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-primary">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (Auth::user()->role == "Admin")
                    <a href="{{ route('stocks.create') }}" class="btn btn-primary mb-3">Tambah Stok</a>
                    @endif
                    <div class="table-responsive">
                        <table id="example1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Cabang</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataStocks as $stock)
                                    @php
                                    $alreadyRequested = \App\Models\Request::where('branch_id', Auth::user()->branch_id)
                                        ->where('variant_id', $stock->variant_id)
                                        ->where('status', 'Pending')
                                        ->exists();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $stock->variant->product->product_name }} ({{ $stock->variant->warna }} - {{ $stock->variant->ukuran }})</td>
                                        <td>{{ $stock->branch->nama_cabang }}</td>
                                        <td>{{ $stock->stock }} Pcs</td>
                                        <td>
                                            @if(Auth::user()->role === 'Cabang' && $stock->stock < 100 && !$alreadyRequested)
                                                <form action="{{ route('requests.store') }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="variant_id" value="{{ $stock->variant_id }}">
                                                    <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
                                                    <input type="hidden" name="stock" value="100">

                                                    <button type="submit" class="btn btn-warning btn-sm">Ajukan Permintaan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================
    SCRIPTS
============================= --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Chart Bar --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('myChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: "Total Transaksi (Rp)",
                data: @json($totals),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: val => 'Rp' + val.toLocaleString('id-ID') }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: { label: ctx => 'Rp' + ctx.parsed.y.toLocaleString('id-ID') }
                }
            }
        }
    });
});
</script>

{{-- Chart Pie --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('topProductPieChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($productNames),
            datasets: [{
                data: @json($totalSold),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' terjual' } }
            }
        }
    });
});
</script>

@endsection
