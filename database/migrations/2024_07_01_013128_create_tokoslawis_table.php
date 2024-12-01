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
        Schema::create('tokoslawis', function (Blueprint $table) {
            $table->id();
            $table->string('member_harga')->nullable();
            $table->string('non_hrga')->nullable();
            $table->string('member_diskon')->nullable();
            $table->string('non_diskon')->nullable();
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
        Schema::dropIfExists('tokoslawis');
    }
};
