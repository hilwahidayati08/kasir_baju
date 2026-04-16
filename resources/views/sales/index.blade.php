@extends('admin.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Daftar Transaksi</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">Tambah Transaksi</a>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <div id="success-alert" class="alert alert-primary">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table id="example1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Total Belanja</th>
                            <th>Total Pembayaran</th>
                            <th>Kasir</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @php $grandTotal = 0; @endphp

                        @forelse($sales as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->customer->customer_name ?? 'Umum' }}</td>

                                <td>Rp. {{ number_format($v->total_amount) }}</td>
                                <td>Rp. {{ number_format($v->payment->amount) }}</td>

                                <td>{{ $v->user->user_name ?? '-' }}</td>
                                <td>{{ ucfirst($v->status) }}</td>
                                <td>{{ $v->created_at->format('d-m-Y') }}</td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- Print Struk --}}
                                        <a href="{{ route('sales.generatid', $v->sales_id) }}"
                                           class="btn btn-warning btn-sm"
                                           target="_blank" data-bs-toggle="tooltip" title="Print Struk">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        {{-- Detail --}}
                                        <a href="{{ route('sales.show', $v->sales_id) }}"
                                           class="btn btn-success btn-sm"
                                           data-bs-toggle="tooltip" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>

                            @php $grandTotal += $v->total_amount; @endphp

                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot style="font-weight:bold;">
                        <tr>
                            <td colspan="3" class="text-center">Total Keseluruhan</td>
                            <td colspan="6">Rp. {{ number_format($grandTotal) }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
    </div>
</div>

{{-- AUTO HIDE ALERT --}}
<script>
    setTimeout(() => {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) successAlert.style.display = "none";
    }, 3000);
</script>

@endsection
