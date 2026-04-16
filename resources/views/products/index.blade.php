@extends('admin.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Data Produk</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Tambah Produk</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div id="success-alert" class="alert alert-primary">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table id="example1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataProducts as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->product_name }}</td>
                                <td>{{ $v->Category->category_name }}</td>
                                        <td>
                                            <form action="{{ route('products.destroy', $v->product_id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus Customer ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('products.edit', $v->product_id) }}"
                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Ubah">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = "none";
        }
    }, 3000);
</script>
@endsection
