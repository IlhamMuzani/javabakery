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
        Schema::create('tokobumiayus', function (Blueprint $table) {
            $table->id();
            $table->string('harga_awal')->nullable();
            $table->string('diskon_awal')->nullable();
            $table->string('member_harga_bmy')->nullable();
            $table->string('non_hrga_bmy')->nullable();
            $table->string('member_diskon_bmy')->nullable();
            $table->string('non_diskon_bmy')->nullable();
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
        Schema::dropIfExists('tokobumiayus');
    }
};
