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
        Schema::create('penjualanproduks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penjualan');
            $table->string('nama_pelanggan');
            $table->string('telp');
            $table->string('alamat');
            $table->string('kategori');
            $table->string('harga');
            $table->string('sub_total');
            $table->string('qrcode_penjualan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualanproduks');
    }
};
