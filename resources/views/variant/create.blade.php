@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Form Tambah Variant</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('variant.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Product --}}
                        <div class="form-group mb-3">
                            <label for="product_id">Produk</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($dataProduct as $v)
                                    <option value="{{ $v->product_id }}">{{ $v->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Warna & Ukuran --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warna" class="form-label">Warna</label>
                                <input type="text" id="warna" name="warna" class="form-control"
                                       value="{{ old('warna') }}" required>
                                @error('warna')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ukuran" class="form-label">Ukuran</label>
                                <select id="ukuran" name="ukuran" class="form-control">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                                @error('ukuran')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Product Price --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="product_price" class="form-label">Harga</label>
                                <input type="number" id="product_price" name="product_price" class="form-control"
                                       value="{{ old('product_price') }}" required>
                                @error('product_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="product_sale" class="form-label">Harga Produk</label>
                                <input type="number" id="product_sale" name="product_sale" class="form-control"
                                       value="{{ old('product_sale') }}" required>
                                @error('product_sale')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Product Photo --}}
                        <div class="row mb-3">
                            <label for="photo" class="col-sm-1 col-form-label">Foto</label>
                            <div class="col-sm-11">
                                <input type="file" id="photo" name="photo" class="form-control">
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('variant.index') }}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
