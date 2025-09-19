<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'payment_method',
        'total_amount',
        'products',
        'trangthai',
    ];

    public $timestamps = true;

    protected $casts = [
        'products' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_amount' => 'integer',
    ];
}
