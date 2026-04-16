@extends('admin.admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Data Kategori</h5>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Tambah Kategori</a>
                </div>
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-hover text-wrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataCategory as $v)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $v->category_name }}</td>
                                        <td>
                                            <form action="{{ route('categories.destroy', $v->category_id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <a href="{{ route('categories.edit', $v->category_id) }}"
                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Ubah">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>

                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?');"
                                                    data-bs-toggle="tooltip" title="Hapus">
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
    </div>
@endsection
