<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
class Sales extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'user_id',
        'customer_id',
        'branch_id',
        'total_amount',
        'discount',
        'status',
        'qris_data',  
        'qris_code', 
    ];

    public function items()
    {
        return $this->hasMany(Salesitem::class, 'sales_id', 'sales_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id'); // Ubah Users menjadi User
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id'); // Ubah Users menjadi User
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'sales_id', 'sales_id');
    }
}