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
    Schema::create('nota', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->string('perihal');
        $table->string('tujuan');
        $table->string('pic');
        $table->string('nomor_nota');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};
