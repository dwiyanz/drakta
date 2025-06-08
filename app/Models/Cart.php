<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $fillable = ['id_user', 'id_produk', 'kuantitas', 'subtotal'];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke tabel products
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }
    
}
