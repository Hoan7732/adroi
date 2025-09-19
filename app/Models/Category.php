<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category'; // Tên bảng
    protected $primaryKey = 'id_ct'; // Khóa chính
    protected $fillable = ['theloai_ct', 'mota_ct'];
    public $timestamps = false; 

    /**
     * Quan hệ với Pages
     */
    public function pages()
    {
        return $this->hasMany(Pages::class, 'category', 'theloai_ct'); 
    }
}