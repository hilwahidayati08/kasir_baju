@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Data Pelanggan</h5>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Tambah Pengguna</a>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div id="success-alert" class="alert alert-primary">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-striped" id="example1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach ($datauser as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->user_name }}</td>
                                <td>{{ $v->user_email }}</td>
                                <td>{{ $v->role }}</td>
                                <td>
                                    <form action="{{ route('users.destroy', $v->user_id) }}" 
                                          method="POST" class="d-inline-flex">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('users.edit', $v->user_id) }}"
                                           class="btn btn-success btn-sm me-2" 
                                           data-bs-toggle="tooltip" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button class="btn btn-danger btn-sm" type="submit"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus?')"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
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

{{-- AUTO HIDE ALERT --}}
<script>
    setTimeout(function() {
        let successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = "none";
        }
    }, 3000);
</script>
@endsection
