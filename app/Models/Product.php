<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_description',
        'category_id'
    ];

    public function variants()
    {
        return $this->hasMany(Variant::class, 'variant_id', 'variant_id');
    }
    public function category()
    {
        return $this->belongsTo(category::class, 'category_id', 'category_id');
    }
}
