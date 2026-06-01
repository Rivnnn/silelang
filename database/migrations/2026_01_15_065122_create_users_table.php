<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Pastikan Tabel Users Siap
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['admin', 'petugas'])->default('petugas');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['admin', 'petugas'])->default('petugas')->after('password');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('role');
                }
            });
        }

        // 2. Tambahkan user_id ke tabel lain
        $relatedTables = ['nasabah', 'surat_keluar', 'memo', 'nota', 'lpas'];
        foreach ($relatedTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'user_id')) {
                        $table->foreignId('user_id')->nullable()->after('id')
                              ->constrained('users')->onDelete('set null');
                    }
                });
            }
        }
    }

    public function down(): void {
        // Opsional: Logika untuk menghapus kolom jika rollback
    }
};