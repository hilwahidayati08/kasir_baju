@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Produk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <!-- Product Name -->
                        <div class="row mb-3">
                            <label for="product_name" class="col-sm-2 col-form-label">Nama Produk</label>
                            <div class="col-sm-10">
                                <input type="text" id="product_name" name="product_name" class="form-control"
                                       value="{{ old('product_name') }}" required />
                                @if ($errors->has('product_name'))
                                    <span class="text-danger">{{ $errors->first('product_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Category -->
                        <div class="row mb-3">
                            <label for="category_id" class="col-sm-2 col-form-label">Kategori Produk</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($dataCategory as $category)
                                        <option value="{{ $category->category_id }}" 
                                            {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="row mb-3">
                            <label for="product_description" class="col-sm-2 col-form-label">Deskripsi Produk</label>
                            <div class="col-sm-10">
                                <textarea id="product_description" name="product_description" class="form-control" required>{{ old('product_description') }}</textarea>
                                @if ($errors->has('product_description'))
                                    <span class="text-danger">{{ $errors->first('product_description') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
