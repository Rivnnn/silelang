<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah pengajuan_lelang_id menjadi nullable langsung via raw SQL
        // Cara ini tidak butuh doctrine/dbal
        DB::statement('ALTER TABLE dana_trr MODIFY pengajuan_lelang_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE dana_trr MODIFY pengajuan_lelang_id BIGINT UNSIGNED NOT NULL');
    }
};