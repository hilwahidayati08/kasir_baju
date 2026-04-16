<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sales;
use App\Models\Product;

class Salesitem extends Model
{
    protected $table = 'sales_items';
    protected $primaryKey = 'sales_items_id';
    protected $fillable = [
        'sales_id',
        'variant_id',
        'quantity',
        'price',
        'total'

    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }
    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'sales_id');
    }
}
