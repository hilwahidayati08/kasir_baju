<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class IndonesiaController extends Controller
{
    // Ambil semua kota berdasarkan KODE provinsi
    public function cities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)->pluck('name', 'code');
        return response()->json($cities);
    }

    // Ambil semua kecamatan berdasarkan KODE kota
    public function districts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)->pluck('name', 'code');
        return response()->json($districts);
    }

    // Ambil semua kelurahan berdasarkan KODE kecamatan
    public function villages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)->pluck('name', 'code');
        return response()->json($villages);
    }
}
