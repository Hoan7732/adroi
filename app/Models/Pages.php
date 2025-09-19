<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'category', 'date', 'gia', 'soluong',
        'mota', 'cauhinhtt', 'cauhinhdx', 'anh'
    ];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'theloai_ct');
    }
}
