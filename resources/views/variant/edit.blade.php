@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Form Ubah Variant</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('variant.update', $dataEditvariant->variant_id) }}" 
                          method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        {{-- Produk --}}
                        <div class="form-group mb-3">
                            <label for="product_id">Produk</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $v)
                                    <option value="{{ $v->product_id }}"
                                        {{ old('product_id', $dataEditvariant->product_id) == $v->product_id ? 'selected' : '' }}>
                                        {{ $v->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Warna & Ukuran --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warna" class="form-label">Warna</label>
                                <input type="text" id="warna" name="warna" class="form-control"
                                       value="{{ old('warna', $dataEditvariant->warna) }}" required>
                                @error('warna')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ukuran" class="form-label">Ukuran</label>
                                <select id="ukuran" name="ukuran" class="form-control">
                                    <option value="">Pilih Ukuran</option>
                                    @foreach (['S','M','L','XL','XXL'] as $size)
                                        <option value="{{ $size }}"
                                            {{ old('ukuran', $dataEditvariant->ukuran) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ukuran')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="product_price" class="form-label">Harga</label>
                                <input type="number" id="product_price" name="product_price" class="form-control"
                                       value="{{ old('product_price', $dataEditvariant->product_price) }}" required>
                                @error('product_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="product_sale" class="form-label">Harga Produk</label>
                                <input type="number" id="product_sale" name="product_sale" class="form-control"
                                       value="{{ old('product_sale', $dataEditvariant->product_sale) }}" required>
                                @error('product_sale')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Foto Lama --}}
                        <div class="mb-3">
                            <label class="form-label">Foto Sekarang</label><br>
                            @if ($dataEditvariant->photo)
                                <img src="{{ asset('uploads/variant/' . $dataEditvariant->photo) }}"
                                     width="120" class="rounded mb-2">
                            @else
                                <p class="text-muted">Tidak ada foto</p>
                            @endif
                        </div>

                        {{-- Foto Baru --}}
                        <div class="row mb-3">
                            <label for="photo" class="col-sm-2 col-form-label">Foto Baru</label>
                            <div class="col-sm-10">
                                <input type="file" id="photo" name="photo" class="form-control">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-warning me-2 text-white">Ubah</button>
                                <a href="{{ route('variant.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
