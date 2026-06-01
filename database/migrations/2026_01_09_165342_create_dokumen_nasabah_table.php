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
        Schema::create('dokumen_nasabah', function (Blueprint $table) {
    $table->id();
    $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
    $table->string('nama_dokumen');
    $table->text('link_dokumen')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_nasabah');
    }
};
