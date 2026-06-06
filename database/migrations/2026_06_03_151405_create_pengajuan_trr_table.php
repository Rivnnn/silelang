<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_trr', function (Blueprint $table) {
            $table->id();

            // Pengajuan TRR ini untuk lelang yang mana
            $table->foreignId('pengajuan_lelang_id')
                  ->constrained('pengajuan_lelang')
                  ->onDelete('cascade');

            // Petugas yang mengajukan
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Nominal yang diminta petugas
            $table->decimal('nominal_diajukan', 15, 2);

            // Keterangan kebutuhan dari petugas
            $table->text('keterangan')->nullable();

            // Status pengajuan TRR
            // pending     = belum diproses admin
            // disetujui   = admin ACC, nominal dicairkan
            // ditolak     = admin tolak
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])
                  ->default('pending');

            // Diisi admin saat ACC: nominal yang disetujui (bisa beda dari yang diajukan)
            $table->decimal('nominal_disetujui', 15, 2)->nullable();

            // Catatan dari admin (alasan tolak atau keterangan ACC)
            $table->text('catatan_admin')->nullable();

            // Admin yang memproses
            $table->foreignId('processed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_trr');
    }
};