@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- Header -->
                <div class="card-header bg-warning">
                    <h5 class="card-title text-white mb-0">Form Ubah Kategori</h5>
                </div>

                <!-- Form -->
                <form action="{{ route('categories.update', $dataEditcategory->category_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <!-- Nama Kategori -->
                        <div class="form-group mb-3">
                            <label for="category_name">Nama Kategori</label>
                            <input type="text"
                                   id="category_name"
                                   name="category_name"
                                   class="form-control @error('category_name') is-invalid @enderror"
                                   value="{{ old('category_name', $dataEditcategory->category_name) }}">
                            @error('category_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning text-white">Ubah</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
