@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- ================= BARIS 1 ================= --}}
    <div class="row">
        {{-- USERS --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-user" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Users</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countuser }}</h3>
                </div>
            </div>
        </div>

        {{-- CUSTOMER --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bx-group" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Customer</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countcustomer }}</h3>
                </div>
            </div>
        </div>

        {{-- PRODUCT --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-box" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Produk</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countproduct }}</h3>
                </div>
            </div>
        </div>

        {{-- VARIANT --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-palette" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Variant</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countvariant }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= BARIS 2 ================= --}}
    <div class="row">
        {{-- CABANG --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-building" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Cabang</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countbranch }}</h3>
                </div>
            </div>
        </div>

        {{-- KATEGORI --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-category" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Kategori</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countcategory }}</h3>
                </div>
            </div>
        </div>

        {{-- REQUEST --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-message-dots" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Request</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $countrequest }}</h3>
                </div>
            </div>
        </div>

        {{-- PENJUALAN HARI INI --}}
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text-center">
                        <i class="bx bxs-cart-alt" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Penjualan Hari Ini</div>
                    </div>
                    <h3 class="mb-0 text-white">{{ $counttotaltoday1 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= BARIS 3 (TRANSAKSI) ================= --}}
    <div class="row">
        {{-- TRANSAKSI HARI INI --}}
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text">
                        <i class="bx bxs-cart" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Hari Ini</div>
                    </div>
                    <h5 class="mb-0 text-white">Rp {{ number_format($counttotaltoday ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        {{-- TRANSAKSI BULAN INI --}}
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text">
                        <i class="bx bxs-calendar" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Bulan Ini</div>
                    </div>
                    <h5 class="mb-0 text-white">Rp {{ number_format($counttotalmonth ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        {{-- TRANSAKSI TAHUN INI --}}
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="text">
                        <i class="bx bxs-calendar-check" style="font-size: 2rem;"></i>
                        <div class="fw-semibold mt-2">Tahun Ini</div>
                    </div>
                    <h5 class="mb-0 text-white">Rp {{ number_format($counttotalyear ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

{{-- ================= GRAFIK DAN REQUEST (DALAM SATU BARIS) ================= --}}
    <div class="row">
        {{-- DAFTAR PERMINTAAN STOK --}}
        <div class="col-md-12 col-sm-12 mb-3">
            <div class="card shadow">
                <h5 class="card-header">Daftar Permintaan Stok</h5>
                <div class="card-body">
                    {{-- Pesan sukses/error --}}
                    @if (session('success'))
                        <div id="success-alert" class="alert alert-primary">{{ session('success') }}</div>
                    @elseif (session('error'))
                        <div id="error-alert" class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="example1" class="table table-striped align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataRequest as $req)
                                    <tr @if($req->status === 'Diterima' && $req->pengiriman === 'Diterima') class="table-success" @endif>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $req->branch->nama_cabang ?? '-' }}</td>
                                        <td>{{ $req->variant->product->product_name ?? '-' }} - {{ $req->variant->warna ?? '-' }} {{ $req->variant->ukuran ?? '-' }}</td>
                                        <td>{{ $req->stock }}</td>
                                        <td>
                                            @if ($req->status === 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($req->status === 'Diterima')
                                                <span class="badge bg-success">Diterima</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($req->pengiriman === 'Dikirim')
                                                <span class="badge bg-info">Dikirim</span>
                                            @elseif ($req->pengiriman === 'Diterima')
                                                <span class="badge bg-success">Diterima</span>
                                            @else
                                                <span class="badge bg-warning">Proses</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Tombol aksi --}}
                                            @if ($req->status === 'Pending')
                                                @if (Auth::user()->role == "Admin")
                                                    <form action="{{ route('requests.updateStatus', $req->request_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Diterima">
                                                        <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                                    </form>
                                                    <form action="{{ route('requests.updateStatus', $req->request_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Ditolak">
                                                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                                    </form>
                                                @endif
                                            @elseif ($req->status === 'Diterima' && $req->pengiriman === 'Proses')
                                                <form action="{{ route('requests.kirim', $req->request_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">Kirim</button>
                                                </form>
                                            @elseif ($req->pengiriman === 'Dikirim')
                                                <form action="{{ route('requests.diterima', $req->request_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Diterima</button>
                                                </form>
                                            @elseif ($req->pengiriman === 'Diterima')
                                                <span class="badge bg-secondary">Selesai</span>
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
    {{-- GRAFIK TRANSAKSI BULANAN --}}
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card shadow">
                    <h5 class="card-header">Laporan Transaksi Bulanan Seluruh Cabang</h5>
                <div class="card-body">
                    <canvas id="myChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Produk Terlaris</h5>
                    <select id="branchFilter" class="form-select form-select-sm" style="width: 200px;">
                        <option value="all">Semua Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->branch_id }}">{{ $branch->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="topProductChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


</div>

{{-- ================= SCRIPTS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myChart');
    if (ctx) {
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
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                    },
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>


<script>
    const ctxTop = document.getElementById('topProductChart').getContext('2d');
    let topProductChart;

    function loadTopProducts(branchId = 'all') {
        fetch(`/dashboard/top-products/${branchId}`)
            .then(res => res.json())
            .then(data => {
                // Jika data kosong
                if (!data || data.length === 0) {
                    if (topProductChart) topProductChart.destroy();
                    topProductChart = new Chart(ctxTop, {
                        type: 'bar',
                        data: {
                            labels: ['Tidak ada data'],
                            datasets: [{
                                label: 'Jumlah Terjual',
                                data: [0],
                                backgroundColor: '#d1d5db'
                            }]
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Top 5 Produk Terlaris',
                                    font: { size: 14 }
                                }
                            },
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                    return;
                }

                // Gabungkan nama produk dan warna
                const labels = data.map(item => `${item.product_name} ( ${item.warna})`);
                const totals = data.map(item => item.total_sold);

                if (topProductChart) topProductChart.destroy();

                topProductChart = new Chart(ctxTop, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: totals,
                            backgroundColor: [
                                '#4e73df',
                                '#1cc88a',
                                '#36b9cc',
                                '#f6c23e',
                                '#e74a3b'
                            ],
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Top 5 Produk Terlaris',
                                font: { size: 14 }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Terjual: ' + context.parsed.y + ' pcs';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 30
                                }
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Gagal memuat data:', err));
    }

    // Load awal
    loadTopProducts();

    // Ubah filter cabang
    document.getElementById('branchFilter').addEventListener('change', function() {
        loadTopProducts(this.value);
    });
</script>


@endsection
