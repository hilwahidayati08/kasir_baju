<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\ValidationException;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataCustomers = Customer::with(['province', 'city', 'district', 'village'])->get();
        return view('customers.index', compact('dataCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = \Laravolt\Indonesia\Models\Province::pluck('name', 'code');
        return view('customers.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    try {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required',
            'member_status'  => 'required|in:0,1',
            'address'        => 'required|string',
            'province_id' => 'required',
            'city_id'     => 'required',
            'district_id' => 'required',
            'village_id'  => 'required',
        ]);
    } catch (ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        throw $e; // default behaviour (redirect back with errors)
    }

    // Simpan dulu -> penting: $customer tersedia sebelum return JSON
    $customer = Customer::create([
        'customer_name'  => $validated['customer_name'],
        'customer_phone' => $validated['customer_phone'],
        'member_status'  => $validated['member_status'],
        'address'        => $validated['address'],
        'province_id'        => $validated['province_id'],
        'city_id'        => $validated['city_id'],
        'district_id'        => $validated['district_id'],
        'village_id'        => $validated['village_id'],
    ]);

    // Jika AJAX/JSON request, kembalikan JSON dengan customer (201 Created)
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['customer' => $customer], 201);
    }
            return redirect(route('customers.index'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $customer = Customer::with(['province', 'city', 'district', 'village'])
        ->findOrFail($id);

    return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::find($id);
        $provinces = \Laravolt\Indonesia\Models\Province::pluck('name', 'code');
        return view('customers.edit', compact('customer', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone'         => 'required',
            'member_status' => 'required',
            'address'       => 'required',
            'province_id' => 'required',
            'city_id'     => 'required',
            'district_id' => 'required',
            'village_id'  => 'required',
        ]);

        $updateDatacustomer = Customer::findOrFail($id);
        $updateDatacustomer->update([
            'customer_name' => $request->customer_name,
            'customer_phone'         => $request->customer_phone,
            'member_status' => $request->member_status,
            'address'       => $request->address,
            'province_id' => $request->province_id,
            'city_id'     => $request->city_id,
            'district_id' => $request->district_id,
            'village_id'  => $request->village_id,
        ]);

        return redirect(route('customers.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::where('customer_id', $id)->delete();
        return redirect(route('customers.index'));
    }
}
