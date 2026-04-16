@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-center">
                            <i class="bx bx-cart-alt" style="font-size: 2rem;"></i>
                            <div class="fw-semibold mt-2">Transaksi Hari Ini</div>
                        </div>
                        <h3 class="mb-0 text-white">{{ $totalTransactions ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-center">
                            <i class="bx bx-money" style="font-size: 2rem;"></i>
                            <div class="fw-semibold mt-2">Total Penjualan</div>
                        </div>
                        <h3 class="mb-0 text-white">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Transaksi --}}
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Grafik Transaksi Mingguan</h5>
                </div>
                <div class="card-body">
                    <canvas id="cashierChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('cashierChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days ?? []) !!},
                datasets: [{
                    label: "Total Transaksi (Rp)",
                    data: {!! json_encode($totals ?? []) !!},
                    borderColor: 'rgba(25, 135, 84, 1)',
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
</script>
@endsection
