@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="card-title text-white mb-0">Tambah Stok Baru</h5>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('stocks.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="variant_id" class="form-label">Produk</label>
                            <select name="variant_id" id="variant_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach($dataVariants as $variant)
                                    <option value="{{ $variant->variant_id }}">
                                        {{ $variant->product->product_name }} ({{ $variant->warna }} - {{ $variant->ukuran }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="branch_id" class="form-label">Cabang</label>
                            <select name="branch_id" id="branch_id" class="form-control" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($dataBranches as $branch)
                                    <option value="{{ $branch->branch_id }}">
                                        {{ $branch->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="stock" class="form-label">Jumlah Stok</label>
                            <input type="number" name="stock" id="stock" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Simpan Stok</button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
