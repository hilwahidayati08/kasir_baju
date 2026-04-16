<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataBranch = Branch::with(['province', 'city', 'district', 'village'])->get();
        return view('branch.index', compact('dataBranch'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $provinces = \Laravolt\Indonesia\Models\Province::pluck('name', 'code');
    return view('branch.create', compact('provinces'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'alamat'      => 'required',
            'kontak'      => 'required',
            'province_id' => 'required',
            'city_id'     => 'required',
            'district_id' => 'required',
            'village_id'  => 'required',
        ]);

        Branch::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat'      => $request->alamat,
            'kontak'      => $request->kontak,
            'province_id' => $request->province_id,
            'city_id'     => $request->city_id,
            'district_id' => $request->district_id,
            'village_id'  => $request->village_id,
        ]);

        return redirect()->route('branch.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataEditbranch = Branch::findOrFail($id);
    $provinces = \Laravolt\Indonesia\Models\Province::pluck('name', 'code');


        return view('branch.edit', compact('dataEditbranch', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'alamat'      => 'required',
            'kontak'      => 'required',
            'province_id' => 'required',
            'city_id'     => 'required',
            'district_id' => 'required',
            'village_id'  => 'required',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat'      => $request->alamat,
            'kontak'      => $request->kontak,
            'province_id' => $request->province_id,
            'city_id'     => $request->city_id,
            'district_id' => $request->district_id,
            'village_id'  => $request->village_id,
        ]);

        return redirect()->route('branch.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Branch::where('branch_id', $id)->delete();
        return redirect()->route('branch.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
