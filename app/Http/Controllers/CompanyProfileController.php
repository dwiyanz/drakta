<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $profil = ProfilPerusahaan::first(); 
        return view('pelanggan.profile', compact('profil'));
    }

    public function edit()
    {
        $profil = ProfilPerusahaan::first(); // Mengambil data profil pertama
    
        if (!$profil) {
            // Jika belum ada data, buat data kosong
            $profil = new ProfilPerusahaan();
        }
        return view('admin.edit-profil-perusahaan', compact('profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul_p1' => 'nullable|string',
            'isi_p1' => 'nullable|string',
            'visi' => 'nullable|string',
            'isi_visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'isi_misi' => 'nullable|string',
            'kontak' => 'nullable|string',
            'isi_kontak' => 'nullable|string',
        ]);
    
        // Cari atau buat profil baru
        $profil = ProfilPerusahaan::firstOrNew();
    
        // Handle logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            $profil->logo = 'storage/' . $path;
        }
    
        // Ambil hanya field yang diizinkan
        $fillableData = $request->only([
            'judul_p1',
            'isi_p1',
            'visi',
            'isi_visi',
            'misi',
            'isi_misi',
            'kontak',
            'isi_kontak'
        ]);
    
        // Format misi dan kontak jika ada
        if (isset($fillableData['isi_misi'])) {
            $fillableData['isi_misi'] = $this->formatMisi($fillableData['isi_misi']);
        }
        if (isset($fillableData['isi_kontak'])) {
            $fillableData['isi_kontak'] = $this->formatKontak($fillableData['isi_kontak']);
        }
    
        // Update data
        foreach ($fillableData as $key => $value) {
            $profil->$key = $value;
        }
        
        // Simpan perubahan
        $profil->save();
    
        return redirect()->route('admin.profil-perusahaan.edit')
            ->with('success', 'Profil Perusahaan berhasil diperbarui!');
    }

public function formatMisi($text)
{
    $lines = array_filter(array_map('trim', explode("\n", $text)));
    return implode("\n", $lines);
}

public function formatKontak($text)
{
    $lines = array_filter(array_map('trim', explode("\n", $text)));
    return implode("\n", $lines);
}



    
}