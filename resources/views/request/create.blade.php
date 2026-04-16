@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Ajukan Permintaan Stok</h5>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('requests.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Cabang</label>
                            <input type="text" class="form-control" value="{{ $databranch->nama_cabang}}" readonly>
                            <input type="hidden" name="branch_id" class="form-control" value="{{$branches}}" readonly>
                        </div>

                        {{-- Input Produk / Varian --}}
                        <div class="mb-3">
                            <label for="variant_id" class="form-label">Produk / Varian</label>
                            <select name="variant_id" id="variant_id" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($variants ?? [] as $variant)
                                    <option value="{{ $variant->variant_id }}">
                                        {{ optional($variant->product)->product_name ?? 'Produk tidak tersedia' }} 
                                        {{ $variant->warna }} {{ $variant->ukuran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Jumlah Stok --}}
                        <div class="mb-3">
                            <label for="stock" class="form-label">Jumlah Stok</label>
                            <input type="number" name="stock" id="stock" class="form-control" min="1" required>
                        </div>

                        {{-- Tombol Kirim Permintaan --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Kirim Permintaan</button>
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
