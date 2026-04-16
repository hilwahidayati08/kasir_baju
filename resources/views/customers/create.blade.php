@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- Header -->
                <div class="card-header bg-primary">
                    <h5 class="card-title text-white mb-0">Form Tambah Pelanggan</h5>
                </div>

                <!-- Form -->
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <!-- Customer Name -->
                        <div class="form-group mb-3">
                            <label for="customer_name">Nama</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
                            @error('customer_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Customer Phone -->
                        <div class="form-group mb-3">
                            <label for="customer_phone">No Telp</label>
                            <input type="number" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Member Status -->
                        <div class="form-group mb-3">
                            <label class="d-block">Member Status</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_yes" name="member_status" value="1" class="form-check-input" {{ old('member_status') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_yes">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_no" name="member_status" value="0" class="form-check-input" {{ old('member_status') == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_no">Tidak Aktif</label>
                            </div>
                            @error('member_status')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group mb-3">
                            <label for="address">Alamat Lengkap</label>
                            <textarea name="address" id="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div class="form-group mb-3">
                            <label for="province_id">Provinsi</label>
                            <select name="province_id" id="province_id" class="form-control">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $code => $name)
                                    <option value="{{ $code }}" {{ old('province_id') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="form-group mb-3">
                            <label for="city_id">Kota / Kabupaten</label>
                            <select name="city_id" id="city_id" class="form-control">
                                <option value="">Pilih Kota / Kabupaten</option>
                            </select>
                            @error('city_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- District -->
                        <div class="form-group mb-3">
                            <label for="district_id">Kecamatan</label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            @error('district_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Village -->
                        <div class="form-group mb-3">
                            <label for="village_id">Kelurahan / Desa</label>
                            <select name="village_id" id="village_id" class="form-control">
                                <option value="">Pilih Kelurahan / Desa</option>
                            </select>
                            @error('village_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT AJAX LANGSUNG DI SINI --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // Provinsi → Kota
    $('#province_id').on('change', function () {
        let provinceCode = $(this).val();
        $('#city_id').html('<option value="">Memuat...</option>');
        $('#district_id').html('<option value="">Pilih Kecamatan</option>');
        $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');

        if (provinceCode) {
            $.get(`/api/cities/${provinceCode}`, function (data) {
                $('#city_id').empty().append('<option value="">Pilih Kota / Kabupaten</option>');
                $.each(data, function (code, name) {
                    $('#city_id').append(`<option value="${code}">${name}</option>`);
                });
            });
        } else {
            $('#city_id').html('<option value="">Pilih Kota / Kabupaten</option>');
        }
    });

    // Kota → Kecamatan
    $('#city_id').on('change', function () {
        let cityCode = $(this).val();
        $('#district_id').html('<option value="">Memuat...</option>');
        $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');

        if (cityCode) {
            $.get(`/api/districts/${cityCode}`, function (data) {
                $('#district_id').empty().append('<option value="">Pilih Kecamatan</option>');
                $.each(data, function (code, name) {
                    $('#district_id').append(`<option value="${code}">${name}</option>`);
                });
            });
        } else {
            $('#district_id').html('<option value="">Pilih Kecamatan</option>');
        }
    });

    // Kecamatan → Kelurahan
    $('#district_id').on('change', function () {
        let districtCode = $(this).val();
        $('#village_id').html('<option value="">Memuat...</option>');

        if (districtCode) {
            $.get(`/api/villages/${districtCode}`, function (data) {
                $('#village_id').empty().append('<option value="">Pilih Kelurahan / Desa</option>');
                $.each(data, function (code, name) {
                    $('#village_id').append(`<option value="${code}">${name}</option>`);
                });
            });
        } else {
            $('#village_id').html('<option value="">Pilih Kelurahan / Desa</option>');
        }
    });
});
</script>
@endsection
