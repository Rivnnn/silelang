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
        Schema::table('dana_trr', function (Blueprint $table) {
            // Ganti pengajuan_lelang_id dengan pengajuan_trr_id
            // Dana TRR sekarang lahir dari pengajuan TRR, bukan langsung dari pengajuan lelang
            $table->foreignId('pengajuan_trr_id')
                ->nullable()
                ->after('id')
                ->constrained('pengajuan_trr')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dana_trr', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_trr_id']);
            $table->dropColumn('pengajuan_trr_id');
        });
    }
};
