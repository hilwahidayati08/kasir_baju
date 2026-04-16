@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <!-- Header -->
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Data Pelanggan</h5>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Tambah Pelanggan</a>
                </div>

                <!-- Table -->
                <div class="card-body table-responsive">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table id="example1" class="table table-hover text-wrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No Telp</th>
                                <th>Member</th>
                                <th>Detail Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataCustomers as $index => $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->customer_phone }}</td>
                                    <td>{{ $customer->member_status ? 'Yes' : 'No' }}</td>
                                    <td>{{ $customer->address }}</td>
                                        <td>
                                            <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus Customer ini?');">
                                                @csrf
                                                @method('DELETE')                                                
                                                <a href="{{ route('customers.show', $customer->customer_id) }}"
                                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer->customer_id) }}"
                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Ubah">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No customers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
