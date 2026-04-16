@extends('admin.admin')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <!-- Header -->
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Data Cabang</h5>
                    <a href="{{ route('branch.create') }}" class="btn btn-primary btn-sm">Tambah Cabang</a>
                </div>

                    <!-- Body -->
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-hover text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Cabang</th>
                                    <th>Kontak</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataBranch as $v)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $v->nama_cabang }}</td>
                                        <td>{{ $v->kontak }}</td>
                                        <td>{{ $v->alamat }}</td>
                                        <td>
                                            <form action="{{ route('branch.destroy', $v->branch_id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus cabang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('branch.edit', $v->branch_id) }}"
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
