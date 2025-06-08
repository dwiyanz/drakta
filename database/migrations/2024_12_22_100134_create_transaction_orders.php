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
        Schema::create('transaction_orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_transaksi')->constrained('transactions');
            $table->string('id_pesanan')->constrained('orders');
            $table->string('dokumen_tambahan')->nullable(); // Path dokumen tambahan (opsional)
            $table->timestamps();
    
            // Foreign key constraints
            $table->foreign('id_transaksi')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id')->on('orders')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
