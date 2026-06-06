<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_dana_trr_table.php
    public function up(): void
    {
        Schema::create('dana_trr', function (Blueprint $table) {
            $table->id();
            
            // Satu dana TRR lahir dari satu pengajuan lelang yang disetujui
            $table->foreignId('pengajuan_lelang_id')
                ->unique() // pastikan satu pengajuan = satu dana TRR saja
                ->constrained('pengajuan_lelang')
                ->onDelete('cascade');
            
            // Admin yang menyetujui
            $table->foreignId('approved_by')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Nomor referensi unik, format: TRR-{TAHUN}-{ID_PADDED}
            // Contoh: TRR-2025-00042
            $table->string('nomor_referensi')->unique();
            
            $table->decimal('nominal_disetujui', 15, 2);
            $table->date('tanggal_cair');
            
            // 'menunggu_konfirmasi': sudah disetujui admin, belum dikonfirmasi petugas
            // 'aktif'              : petugas sudah konfirmasi, lelang sedang berjalan
            // 'selesai'            : lelang selesai, LPJ sudah dibuat
            $table->enum('status', ['menunggu_konfirmasi', 'aktif', 'selesai'])
                ->default('menunggu_konfirmasi');
            
            // Diisi saat petugas menekan tombol "Konfirmasi Penerimaan Dana"
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dana_trr');
    }
};
