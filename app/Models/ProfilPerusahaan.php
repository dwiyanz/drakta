<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'profil_perusahaan'; // Menentukan nama tabel jika berbeda dengan nama model

    protected $primaryKey = 'id';

    protected $fillable = [
        'logo',
        'judul_p1',
        'isi_p1',
        'visi',
        'isi_visi',
        'misi',
        'isi_misi',
        'kontak',
        'isi_kontak'
    ]; // Menentukan kolom yang dapat diisi
}
