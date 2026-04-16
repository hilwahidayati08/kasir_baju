<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'sales_id',
        'user_id',
        'amount',
        'change',
        'payment_method',
        'payment_date',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'sales_id');
    }

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }
}
