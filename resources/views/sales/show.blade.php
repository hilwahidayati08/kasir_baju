@extends('admin.admin')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Detail Transaksi #{{ $sales->sales_id }}-{{ $sales->branch->nama_cabang }}</h5>
            <div class="card-body">

                {{-- Status & Tanggal --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Status Transaksi</h6>
                                <p>{{ ucfirst($sales->status) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Tanggal Pembayaran</h6>
                                <p>{{ $sales->payment->payment_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customer & Payment --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Customer</h6>
                                <p>{{ $sales->customer->customer_name ?? 'Guest' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Metode Pembayaran</h6>
                                <p>{{ $sales->payment->payment_method }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Produk --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Produk yang Dibeli</h6>
                        <ul class="list-group">
                            @foreach ($sales->items as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $detail->variant->product->product_name }}</strong><br>
                                        @if ($detail->variant)
                                            Variant: {{ $detail->variant->warna }} - {{ $detail->variant->ukuran }}<br>
                                        @endif
                                        Harga Satuan: Rp.
                                        {{ number_format($detail->variant->product_sale) }}<br>
                                        Jumlah: {{ $detail->quantity }}
                                    </div>
                                    <div class="product-img">
                                        @if ($detail->variant->photo)
                                            <img src="{{ asset('storage/' . $detail->variant->photo) }}"
                                                alt="{{ $detail->variant->product->product_name }}"
                                                style="width: 80px; border-radius: 8px;">
                                        @else
                                            <img src="https://via.placeholder.com/80" alt="No Image"
                                                style="border-radius: 8px;">
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Ringkasan Pembayaran --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Ringkasan Pembayaran</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6>Total Harga</h6>
                                        <p>Rp. {{ number_format($sales->total_amount) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6>Diskon</h6>
                                        <p>Rp. {{ number_format($sales->discount) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6>Pembayaran</h6>
                                        <p>Rp. {{ number_format($sales->payment->amount) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6>Kembalian</h6>
                                        <p>Rp. {{ number_format($sales->payment->change) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back Button --}}
                <div class="mt-4">
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
