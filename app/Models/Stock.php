<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{

    use HasFactory;

    protected $table = 'stocks';
    protected $primaryKey = 'stock_id';
    protected $fillable = [
        'stock',
        'variant_id',
        'branch_id',
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}