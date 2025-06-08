<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key custom
            $table->string('name'); // Kolom untuk nama lengkap
            $table->string('username');// Kolom nama
            $table->string('email')->unique(); // Email harus unik
            $table->string('password'); // Password
            $table->string('telepon'); // Nomor telepon
            $table->text('alamat'); // Alamat
            $table->string('role')->default('pelanggan'); // Role default 'pelanggan'
            $table->boolean('is_admin')->default(false); // Kolom is_admin
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}


