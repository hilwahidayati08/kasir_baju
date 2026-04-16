<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Model\Sales;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
        use Notifiable;
    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'user_email',
        'password',
        'role',
        'branch_id',
        'photo',

    ];

    public function sales()
    {
        return $this->hasMany(Sales::class, 'user_id', 'user_id');
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

}
