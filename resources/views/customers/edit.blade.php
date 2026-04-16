@extends('admin.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- Header -->
                <div class="card-header bg-warning">
                    <h5 class="card-title text-white mb-0">Form Ubah Pelanggan</h5>
                </div>

                <!-- Form -->
                <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <!-- Customer Name -->
                        <div class="form-group mb-3">
                            <label for="customer_name">Nama</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   class="form-control"
                                   value="{{ old('customer_name', $customer->customer_name) }}">
                            @error('customer_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Customer Phone -->
                        <div class="form-group mb-3">
                            <label for="customer_phone">No Telp</label>
                            <input type="number" name="customer_phone" id="customer_phone" 
                                   class="form-control"
                                   value="{{ old('customer_phone', $customer->customer_phone) }}">
                            @error('customer_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Member Status -->
                        <div class="form-group mb-3">
                            <label class="d-block">Member Status</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_yes" name="member_status" value="1" 
                                       class="form-check-input"
                                       {{ old('member_status', $customer->member_status) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_yes">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_no" name="member_status" value="0" 
                                       class="form-check-input"
                                       {{ old('member_status', $customer->member_status) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_no">Tidak Aktif</label>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group mb-3">
                            <label for="address">Alamat Lengkap</label>
                            <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
                        </div>

                        <!-- Province -->
                        <div class="form-group mb-3">
                            <label for="province_id">Provinsi</label>
                            <select name="province_id" id="province_id" class="form-control">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $code => $name)
                                    <option value="{{ $code }}" 
                                        {{ old('province_id', $customer->province_id) == $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- County / City -->
                        <div class="form-group mb-3">
                            <label for="city_id">Kota / Kabupaten</label>
                            <select name="city_id" id="city_id" class="form-control">
                                <option value="">Memuat...</option>
                            </select>
                        </div>

                        <!-- District -->
                        <div class="form-group mb-3">
                            <label for="district_id">Kecamatan</label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Memuat...</option>
                            </select>
                        </div>

                        <!-- Village -->
                        <div class="form-group mb-3">
                            <label for="village_id">Kelurahan / Desa</label>
                            <select name="village_id" id="village_id" class="form-control">
                                <option value="">Memuat...</option>
                            </select>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning text-white">Ubah</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

{{-- AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    let oldCity = "{{ old('city_id', $customer->city_id) }}";
    let oldDistrict = "{{ old('district_id', $customer->district_id) }}";
    let oldVillage = "{{ old('village_id', $customer->village_id) }}";

    // Load cities when page load
    if ($("#province_id").val()) {
        loadCities($("#province_id").val(), oldCity);
    }

    // Load districts when page load
    if (oldCity) {
        loadDistricts(oldCity, oldDistrict);
    }

    // Load villages when page load
    if (oldDistrict) {
        loadVillages(oldDistrict, oldVillage);
    }

    // Province → City
    $('#province_id').on('change', function () {
        let code = $(this).val();
        loadCities(code, null);
    });

    // City → District
    $('#city_id').on('change', function () {
        let code = $(this).val();
        loadDistricts(code, null);
    });

    // District → Village
    $('#district_id').on('change', function () {
        let code = $(this).val();
        loadVillages(code, null);
    });

    function loadCities(provinceCode, selected = null) {
        $('#city_id').html('<option value="">Memuat...</option>');
        $.get(`/api/cities/${provinceCode}`, function (data) {
            $('#city_id').empty().append('<option value="">Pilih Kota / Kabupaten</option>');
            $.each(data, function (code, name) {
                $('#city_id').append(`<option value="${code}" ${selected == code ? 'selected' : ''}>${name}</option>`);
            });
        });
    }

    function loadDistricts(cityCode, selected = null) {
        $('#district_id').html('<option value="">Memuat...</option>');
        $.get(`/api/districts/${cityCode}`, function (data) {
            $('#district_id').empty().append('<option value="">Pilih Kecamatan</option>');
            $.each(data, function (code, name) {
                $('#district_id').append(`<option value="${code}" ${selected == code ? 'selected' : ''}>${name}</option>`);
            });
        });
    }

    function loadVillages(districtCode, selected = null) {
        $('#village_id').html('<option value="">Memuat...</option>');
        $.get(`/api/villages/${districtCode}`, function (data) {
            $('#village_id').empty().append('<option value="">Pilih Kelurahan / Desa</option>');
            $.each(data, function (code, name) {
                $('#village_id').append(`<option value="${code}" ${selected == code ? 'selected' : ''}>${name}</option>`);
            });
        });
    }

});
</script>

@endsection
