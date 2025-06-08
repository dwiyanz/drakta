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
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary();      // ID Transaksi dengan format TR0001                   // ID Produk dari tabel products
            $table->string('id_user')->constrained('users');    // Foreign key untuk tabel users (id_user)
            $table->string('id_pesanan')->constrained('orders');    // Foreign key untuk tabel users (id_user)
            $table->decimal('total_pembayaran', 15, 2);
            $table->string('snap_token')->nullable();
            $table->string('status')->default('pending');
            $table->date('tanggal_transaksi')->nullable(); 
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade'); // Relasi ke users
            $table->foreign('id_pesanan')->references('id')->on('orders')->onDelete('cascade'); // Relasi ke users
            $table->timestamps();                            // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
