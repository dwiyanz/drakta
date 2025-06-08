<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class DetailTransaction extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai konvensi Laravel
    protected $table = 'transaction_orders';

    // Tentukan primary key tabel
    protected $primaryKey = 'id';

    public $incrementing = false; // Karena id_pesanan kemungkinan adalah string
    protected $keyType = 'string'; // Pastikan key type adalah string


    // Tentukan kolom-kolom yang dapat diisi
    protected $fillable = [
        'id_transaksi',
        'id_pesanan',
        'dokumen_tambahan',
    ];

    // Relasi ke model Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id');
    }

// Model DetailTransaction.php
    public function product()
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id')
                    ->belongsTo(Product::class, 'id_produk', 'id');
    }

    

    // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transactiondetail) {
            $lastOrder = self::orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? (int)Str::after($lastOrder->id, 'TRD') : 0;
            $transactiondetail->id = 'TRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Assign id_transaksi
        });
    }
}
