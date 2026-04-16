@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Daftar Permintaan Stok</h5>

        <div class="card-body">

            {{-- Pesan sukses / error --}}
            @if (session('success'))
                <div id="alertBox" class="alert alert-primary">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div id="alertBox" class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive mt-3">
                <table id="example1" class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Cabang</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataRequest as $req)
                            <tr @if($req->status === 'Diterima' && $req->pengiriman === 'Diterima') class="table-success" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->branch->nama_cabang ?? '-' }}</td>
                                <td>
                                    {{ $req->variant->product->product_name ?? '-' }}
                                    - {{ $req->variant->warna ?? '-' }}
                                    {{ $req->variant->ukuran ?? '-' }}
                                </td>
                                <td>{{ $req->stock }}</td>

                                <td>
                                    @if ($req->status === 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($req->status === 'Diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($req->pengiriman === 'Dikirim')
                                        <span class="badge bg-info">Dikirim</span>
                                    @elseif ($req->pengiriman === 'Diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-warning">Proses</span>
                                    @endif
                                </td>

                                <td>
                                    {{-- STATUS PENDING --}}
                                    @if ($req->status === 'Pending')
                                        @if (Auth::user()->role == "Admin")
                                            <form action="{{ route('requests.updateStatus', $req->request_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Diterima">
                                                <button class="btn btn-success btn-sm">Terima</button>
                                            </form>

                                            <form action="{{ route('requests.updateStatus', $req->request_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif

                                    {{-- STATUS DITERIMA & PENGIRIMAN PROSES --}}
                                    @elseif ($req->status === 'Diterima' && $req->pengiriman === 'Proses')
                                        <form action="{{ route('requests.kirim', $req->request_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-info btn-sm">Kirim</button>
                                        </form>

                                    {{-- SUDAH DIKIRIM --}}
                                    @elseif ($req->pengiriman === 'Dikirim')
                                        <form action="{{ route('requests.diterima', $req->request_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Diterima</button>
                                        </form>

                                    {{-- SELESAI --}}
                                    @elseif ($req->pengiriman === 'Diterima')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('requests.create') }}" class="btn btn-primary mt-4">+ Ajukan Permintaan Stok</a>

        </div>
    </div>
</div>

{{-- Auto hide alert --}}
<script>
    setTimeout(() => {
        const alertBox = document.getElementById('alertBox');
        if (alertBox) alertBox.style.display = 'none';
    }, 3000);
</script>
@endsection
