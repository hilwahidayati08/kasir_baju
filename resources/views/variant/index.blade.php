@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Data Variant</h5>
            <a href="{{ route('variant.create') }}" class="btn btn-primary btn-sm">Tambah Variant</a>
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
                            <th>Produk</th>
                            <th>Warna</th>
                            <th>Ukuran</th>
                            <th>Harga</th>
                            <th>Harga Jual</th>
                            <th>Barcode</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach ($dataVariant as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->product->product_name }}</td>
                                <td>{{ $product->warna }}</td>
                                <td>{{ $product->ukuran }}</td>
                                <td>Rp. {{ number_format($product->product_price) }}</td>
                                <td>Rp. {{ number_format($product->product_sale) }}</td>

                                {{-- BARCODE --}}
                                <td>
                                    {!! DNS1D::getBarcodeHTML($product->barcode, 'C128', 1.2, 25) !!}
                                    <div style="font-size: 9px; text-align:center;">
                                        {{ $product->barcode }}
                                    </div>
                                </td>

                                {{-- FOTO --}}
                                <td>
                                    @if($product->photo)
                                        <img src="{{ asset('storage/' . $product->photo) }}"
                                             alt="Foto Variant"
                                             width="70" style="object-fit:cover;">
                                    @else
                                        <img src="https://via.placeholder.com/70"
                                             alt="No Image"
                                             width="70">
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('variant.print', $product->variant_id) }}"
                                           class="btn btn-info btn-sm"
                                           target="_blank"
                                           data-bs-toggle="tooltip"
                                           title="Print">
                                            <i class="fas fa-barcode"></i>
                                        </a>

                                        <a href="{{ route('variant.edit', $product->variant_id) }}"
                                           class="btn btn-warning btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Ubah">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>

                                        <form action="{{ route('variant.destroy', $product->variant_id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Anda yakin ingin menghapus variant ini?')"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus">
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
        let successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = "none";
        }
    }, 3000);
</script>

@endsection
