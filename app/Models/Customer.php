<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'address',
        'member_status',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
    ];

    // === Relasi ke tabel IndoRegion ===
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    // === Relasi ke tabel Sales (perbaikan) ===
    public function sales()
    {
        return $this->hasMany(Sales::class, 'customer_id', 'customer_id');
    }
}
