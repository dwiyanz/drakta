<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilPerusahaanTable extends Migration
{
    public function up()
    {
        Schema::create('profil_perusahaan', function (Blueprint $table) {
            $table->id('id');
            $table->string('logo')->nullable(); // Menyimpan logo perusahaan
            $table->string('judul_p1')->nullable(); // Judul untuk section pertama
            $table->text('isi_p1')->nullable(); // Isi untuk section pertama
            $table->string('visi')->nullable(); // Menyimpan visi perusahaan
            $table->text('isi_visi')->nullable(); // Isi dari visi perusahaan
            $table->string('misi')->nullable(); // Menyimpan misi perusahaan
            $table->text('isi_misi')->nullable(); // Isi dari misi perusahaan
            $table->string('kontak')->nullable(); // Menyimpan kontak perusahaan
            $table->text('isi_kontak')->nullable(); // Isi dari kontak perusahaan
            $table->timestamps(); // Menyimpan waktu created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('profil_perusahaan');
    }
}
