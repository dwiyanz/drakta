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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['saldo', 'transfer'])->default('transfer')->after('total_pembayaran');
            $table->string('status_pembayaran')->default('pending')->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
            $table->dropColumn('status_pembayaran');
        });
    }
};
