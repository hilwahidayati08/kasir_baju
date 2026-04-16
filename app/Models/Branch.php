<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Branch extends Model
{
    protected $table = 'branch';
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'kontak',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
    ];

    // === Relasi IndoRegion ===
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

    // === Relasi ke tabel lain ===
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'branch_id', 'branch_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'branch_id', 'branch_id');
    }

    public function users()
    {
        return $this->hasMany(Users::class, 'branch_id', 'branch_id');
    }
}
