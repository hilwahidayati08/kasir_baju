@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Daftar Stok</h5>

            @if (Auth::user()->role == "Admin")
                <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
                    Tambah Stok
                </a>
            @endif
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div id="success-alert" class="alert alert-primary">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ALERT ERROR --}}
            @if(session('error'))
                <div id="error-alert" class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-striped" id="example1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Cabang</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach($dataStocks as $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $stock->variant->product->product_name }}
                                    ({{ $stock->variant->warna }} - {{ $stock->variant->ukuran }})
                                </td>

                                <td>{{ $stock->branch->nama_cabang }}</td>

                                <td>{{ $stock->stock }} Pcs</td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- PERMINTAAN STOK (Cabang) --}}
                                        @if(Auth::user()->role === 'Cabang' && $stock->stock < 100)
                                            <form action="{{ route('requests.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="variant_id" value="{{ $stock->variant_id }}">
                                                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
                                                <input type="hidden" name="stock" value="100">

                                                <button type="submit" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="tooltip" title="Ajukan Permintaan">
                                                    Ajukan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- HAPUS --}}
                                        <form action="{{ route('stocks.destroy', $stock->stock_id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus stok ini?')"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                    </div>
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
        const successAlert = document.getElementById('success-alert');
        if (successAlert) successAlert.style.display = "none";

        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) errorAlert.style.display = "none";
    }, 3000);
</script>

@endsection
