<?php

namespace App\Http\Controllers;

use App\Models\Beranda;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // Jika tidak ada record, kembalikan instance baru
        $beranda = Beranda::first() ?? new Beranda();

        return view('pelanggan.home', compact('beranda'));
    }
    public function edit()
    {
        $beranda = Beranda::firstOrNew(); // Menggunakan firstOrNew() langsung
        return view('admin.edit-beranda', compact('beranda'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_carousel1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link1_g1' => 'required|string',
            'gambar_carousel2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link1_g2' => 'required|string',
            'gambar_carousel3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link1_g3' => 'required|string',
            'sec2_text1' => 'required|string',
            'sec2_text2' => 'required|string',
            'sec2_text3' => 'required|string',
            'sec3_judul' => 'required|string',
            'sec3_text1' => 'required|string',
            'sec3_text2' => 'required|string',
            'sec3_text3' => 'required|string',
            'sec3_map' => 'required|string',
        ]);

        $beranda = Beranda::firstOrNew(); // Menggunakan firstOrNew() untuk mendapatkan atau membuat record baru

        // Handle file uploads
        if ($request->hasFile('gambar_utama')) {
            $file = $request->file('gambar_utama');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_utama = 'storage/' . $path;
        }
    
        if ($request->hasFile('gambar_carousel1')) {
            $file = $request->file('gambar_carousel1');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_carousel1 = 'storage/' . $path;
        }
        $beranda->link1_g1 = $request->link1_g1;
        
        if ($request->hasFile('gambar_carousel2')) {
            $file = $request->file('gambar_carousel2');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_carousel2 = 'storage/' . $path;
        }
        $beranda->link1_g2 = $request->link1_g2;

        if ($request->hasFile('gambar_carousel3')) {
            $file = $request->file('gambar_carousel3');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_carousel3 = 'storage/' . $path;
        }
        $beranda->link1_g3 = $request->link1_g3;


        $beranda->sec2_text1 = $request->sec2_text1;
        $beranda->sec2_text2 = $request->sec2_text2;
        $beranda->sec2_text3 = $request->sec2_text3;
        $beranda->sec3_judul = $request->sec3_judul;
        $beranda->sec3_text1 = $request->sec3_text1;
        $beranda->sec3_text2 = $request->sec3_text2;
        $beranda->sec3_text3 = $request->sec3_text3;
        $beranda->sec3_map = $request->sec3_map;
    
        $beranda->save();
        
        return redirect()->route('admin.beranda.edit')->with('success', 'Beranda berhasil diperbarui!');
    }
}