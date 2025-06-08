<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'id'; // Set primary key ke 'id_pesanan'
    public $incrementing = false; // Karena id_pesanan kemungkinan adalah string
    protected $keyType = 'string'; // Pastikan key type adalah string

    protected $fillable = [
        'id_produk',
        'id_user',
        'kuantitas',
        'total_pembayaran',
    ];

    // Relasi ke model Product
    // Relasi ke model Product
    // Order Model
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    // Product Model
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_produk', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_pesanan', 'id'); // Sesuaikan foreign key
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(DetailTransaction::class, 'id_pesanan', 'id');
    }


    // Generate id_pesanan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrder = self::lockForUpdate()->orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? (int) Str::after($lastOrder->id, 'PSN') : 0;
            $order->id = 'PSN' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }



}
