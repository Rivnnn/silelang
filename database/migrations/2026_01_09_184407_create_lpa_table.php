<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lpas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nasabah_id');
        
            $table->string('jenis_legalitas');
            $table->double('luas_tanah');
            $table->double('luas_bangunan');
            $table->text('spek_bangunan');
        
            $table->double('nilai_pasar');
            $table->double('nilai_likuidasi');
        
            $table->integer('lelang_ke');
            $table->double('nilai_limit');
            $table->double('uang_jaminan');
        
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('lpa');
    }
};
