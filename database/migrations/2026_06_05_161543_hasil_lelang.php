<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan hasil akhir setiap lelang:
     * - berapa harga terjual
     * - siapa pemenang
     * - berapa sisa dana TRR yang dikembalikan
     * - catatan petugas
     * Satu dana TRR yang sudah selesai → satu baris hasil_lelang
     */
    public function up(): void
    {
        Schema::create('hasil_lelang', function (Blueprint $table) {
            $table->id();

            // Dana TRR yang sudah berstatus 'selesai'
            $table->foreignId('dana_trr_id')
                ->unique()
                ->constrained('dana_trr')
                ->onDelete('cascade');

            // Harga penawaran tertinggi yang diterima saat lelang berlangsung
            $table->decimal('harga_terjual', 15, 2)->nullable();

            // Nama pemenang / pembeli (opsional, bisa perorangan / badan usaha)
            $table->string('nama_pemenang', 255)->nullable();

            // Tanggal pelaksanaan lelang
            $table->date('tanggal_lelang')->nullable();

            // Total biaya yang sudah direalisasikan dari kas TRR
            // (diambil otomatis dari SUM(debet) ledger, disimpan sebagai snapshot)
            $table->decimal('total_biaya_realisasi', 15, 2)->default(0);

            // Sisa saldo TRR yang dikembalikan ke kas
            $table->decimal('sisa_dana_dikembalikan', 15, 2)->default(0);

            // Status hasil: berhasil = terjual, gagal = tidak ada pemenang
            $table->enum('status_hasil', ['berhasil', 'gagal'])->default('berhasil');

            // Catatan umum dari petugas setelah lelang selesai
            $table->text('catatan')->nullable();

            // Petugas yang menginput hasil lelang
            $table->foreignId('diinput_oleh')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_lelang');
    }
};