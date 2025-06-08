<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();  // Primary key custom
            $table->string('nama_produk');          // Nama produk
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);        // Harga dengan 2 angka desimal
            $table->string('bahan');    // Bahan (opsional)
            $table->string('ukuran');   // Ukuran (opsional)
            $table->integer('stok')->default(0);    // Stok default 0
            $table->string('gambar');   // Path gambar (opsional)
            $table->timestamps();                  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
