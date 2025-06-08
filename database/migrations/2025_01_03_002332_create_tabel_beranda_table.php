<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelBerandaTable extends Migration
{
    public function up()
    {
        Schema::create('tabel_beranda', function (Blueprint $table) {
            $table->id('id');
            $table->string('gambar_utama');
            $table->string('gambar_carousel1');
            $table->string('link1_g1');
            $table->string('gambar_carousel2');
            $table->string('link1_g2');
            $table->string('gambar_carousel3');
            $table->string('link1_g3');
            $table->text('sec2_text1');
            $table->text('sec2_text2');
            $table->text('sec2_text3');
            $table->string('sec3_judul');
            $table->text('sec3_text1');
            $table->text('sec3_text2');
            $table->text('sec3_text3');
            $table->text('sec3_map');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tabel_beranda');
    }
}
