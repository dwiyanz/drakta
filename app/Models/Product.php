<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Case-sensitive
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_produk',
        'deskripsi',
        'harga',
        'bahan',
        'ukuran',
        'stok',
        'gambar',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order (pilih salah satu: hasMany atau hasOne)
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_produk', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->id)) {
                $lastProduct = self::orderBy('id', 'desc')->first();
                $lastNumber = $lastProduct ? (int)Str::after($lastProduct->id, 'PRD') : 0;
                $product->id = 'PRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}