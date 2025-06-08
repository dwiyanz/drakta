<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;
    protected $primaryKey = 'id'; // Primary key adalah id_pelanggan
    public $incrementing = false;          // Non-auto increment karena format custom
    protected $keyType = 'string';         // Tipe primary key adalah string

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'telepon',
        'alamat',
        'role',
        'saldo',
        'jenis_rekening', // <-- Tambahkan baris ini
        'no_rekening',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_pesanan','id'); // Relasi dengan Order, bukan Product
    }

     protected static function boot()
     {
         parent::boot();
 
         static::creating(function ($user) {
             // Ambil ID terakhir dari database
             $lastUser = self::orderBy('id', 'desc')->first();
             // Cek apakah ada user sebelumnya
             $lastNumber = $lastUser ? (int)Str::after($lastUser->id, 'USR') : 0;
             // Generate ID baru dengan menambah satu pada nomor terakhir, lalu pad dengan 4 digit angka
             $user->id = 'USR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
         });
     }

}

