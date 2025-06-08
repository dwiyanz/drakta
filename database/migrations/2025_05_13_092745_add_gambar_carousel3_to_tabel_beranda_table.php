<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('tabel_beranda', function (Blueprint $table) {
        $table->string('gambar_carousel3')->nullable();
    });
}

public function down()
{
    Schema::table('tabel_beranda', function (Blueprint $table) {
        $table->dropColumn('gambar_carousel3');
    });
}

};
