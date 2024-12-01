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
        Schema::create('tokobenjarans', function (Blueprint $table) {
            $table->id();
            $table->string('member_harga_bnjr')->nullable();
            $table->string('non_hrga_bnjr')->nullable();
            $table->string('member_diskon_bnjr')->nullable();
            $table->string('non_diskon_bnjr')->nullable();
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
        Schema::dropIfExists('tokobenjarans');
    }
};
