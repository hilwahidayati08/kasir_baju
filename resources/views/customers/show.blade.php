@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <h5 class="card-header">Detail Pelanggan #{{ $customer->customer_id }}</h5>

        <div class="card-body">

            {{-- Status & Kontak --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Status Member</h6>
                            <p>{{ $customer->member_status == 1 ? 'Member' : 'Non Member' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>No Telp</h6>
                            <p>{{ $customer->customer_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nama & Email --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Nama Pelanggan</h6>
                            <p>{{ $customer->customer_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Email</h6>
                            <p>{{ $customer->user_email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">Alamat Lengkap</h6>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6>Provinsi</h6>
                                    <p>{{ \Laravolt\Indonesia\Models\Province::where('code', $customer->province_id)->value('name') ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6>Kota/Kabupaten</h6>
                                    <p>{{ \Laravolt\Indonesia\Models\City::where('code', $customer->city_id)->value('name') ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6>Kecamatan</h6>
                                    <p>{{ \Laravolt\Indonesia\Models\District::where('code', $customer->district_id)->value('name') ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6>Kelurahan/Desa</h6>
                                    <p>{{ \Laravolt\Indonesia\Models\Village::where('code', $customer->village_id)->value('name') ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="mt-3">Detail Alamat</h6>
                            <p>{{ $customer->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back --}}
            <div class="mt-3">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
