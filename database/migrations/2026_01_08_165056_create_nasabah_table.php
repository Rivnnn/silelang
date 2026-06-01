<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('nasabah', function (Blueprint $table) {
        $table->id();
        $table->string('nama_nasabah');
        $table->string('nik')->unique();
        $table->text('alamat');
        $table->string('no_hp');
        $table->string('lokasi_lelang');
        $table->string('jenis_lelang');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};
