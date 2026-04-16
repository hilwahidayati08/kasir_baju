@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Header -->
                <div class="card-header bg-primary">
                    <h5 class="card-title text-white mb-0">Edit Stok</h5>
                </div>

                <!-- Form Edit Stok -->
                <form action="{{ route('stocks.update', $dataEditStock->stock_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        
                        <!-- Pemilihan Produk -->
                        <div class="form-group mb-3">
                            <label for="variant_id" class="form-label">Produk</label>
                            <select name="variant_id" id="variant_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->variant_id }}" 
                                        @if($variant->variant_id == $dataEditStock->variant_id) selected @endif>
                                        {{ $variant->product->product_name }} ({{ $variant->warna }} - {{ $variant->ukuran }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pemilihan Cabang -->
                        <div class="form-group mb-3">
                            <label for="branch_id" class="form-label">Cabang</label>
                            <select name="branch_id" id="branch_id" class="form-control" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}" 
                                        @if($branch->branch_id == $dataEditStock->branch_id) selected @endif>
                                        {{ $branch->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah Stok -->
                        <div class="form-group mb-3">
                            <label for="stock" class="form-label">Jumlah Stok</label>
                            <input type="number" name="stock" id="stock" class="form-control" 
                                value="{{ $dataEditStock->stock }}" required>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Update Stok</button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
