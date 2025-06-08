<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('id'); // Primary key
            $table->string('id_user'); // Foreign key ke tabel users
            $table->string('id_produk'); // Foreign key ke tabel products
            $table->integer('kuantitas')->default(1); // Jumlah produk dalam keranjang
            $table->decimal('subtotal', 15, 2); // Subtotal (harga x kuantitas)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
