<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        /**
         * Run the migrations.
         */
        // database/migrations/xxxx_create_trr_ledger_table.php
    public function up(): void
    {
        Schema::create('trr_ledger', function (Blueprint $table) {
            $table->id();
            
            // Catatan ini milik dana TRR yang mana
            $table->foreignId('dana_trr_id')
                ->constrained('dana_trr')
                ->onDelete('cascade');
            
            $table->string('keterangan'); // Contoh: "Biaya pengumuman koran"
            
            // Kredit = pemasukan (biasanya hanya baris pertama = dana awal)
            $table->decimal('kredit', 15, 2)->default(0);
            
            // Debet = pengeluaran (yang diisi petugas setiap ada biaya)
            $table->decimal('debet', 15, 2)->default(0);
            
            // Sisa saldo SETELAH transaksi ini
            // Dihitung otomatis di Controller sebelum disimpan
            $table->decimal('sisa_saldo', 15, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trr_ledger');
    }
};
