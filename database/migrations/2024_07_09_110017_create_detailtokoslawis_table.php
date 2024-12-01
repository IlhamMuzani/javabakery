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
        Schema::create('detailtokoslawis', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id')->nullable();
            $table->string('tokoslawi_id')->nullable();
            $table->string('member_harga')->nullable();
            $table->string('non_member_harga')->nullable();
            $table->string('member_diskon')->nullable();
            $table->string('non_member_diskon')->nullable();
            $table->string('harga_diskon_member')->nullable();
            $table->string('harga_diskon_non')->nullable();
            $table->string('harga_awal')->nullable();
            $table->string('diskon_awal')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('detailtokoslawis');
    }
};
