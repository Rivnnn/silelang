<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NasabahSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // -------------------------------------------------------
        // 1. Ambil user_id petugas
        // -------------------------------------------------------
        $petugas = DB::table('users')->where('email', 'petugas@silelang.com')->first();

        if (!$petugas) {
            $this->command->error('❌ Petugas tidak ditemukan! Jalankan AdminSeeder terlebih dahulu.');
            return;
        }

        $userId = $petugas->id;

        // -------------------------------------------------------
        // 2. Bersihkan data lama agar tidak tabrakan
        //    (urutan penting karena ada foreign key)
        // -------------------------------------------------------
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lpas')->truncate();
        DB::table('dokumen_nasabah')->truncate();
        DB::table('nasabah')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // -------------------------------------------------------
        // 3. Data referensi
        // -------------------------------------------------------
        $lokasiLelang = [
            'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara',
            'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Makassar',
            'Yogyakarta', 'Palembang', 'Denpasar', 'Balikpapan', 'Bogor',
        ];

        $jenisLelang = [
            'Lelang Eksekusi HT',
            'Lelang Non Eksekusi Wajib',
            'Lelang Sukarela',
            'Lelang Eksekusi Pengadilan',
            'Lelang Aset BUMN',
        ];

        $jenisDokumen = [
            'Sertifikat Hak Milik (SHM)',
            'Sertifikat Hak Guna Bangunan (SHGB)',
            'IMB / PBG',
            'KTP Nasabah',
            'NPWP Nasabah',
            'Akta Jual Beli',
            'Perjanjian Kredit',
            'Surat Kuasa',
            'SPPT PBB',
            'Fotokopi KK',
        ];

        $jenisLegalitas = [
            'SHM (Sertifikat Hak Milik)',
            'SHGB (Sertifikat Hak Guna Bangunan)',
            'SHGU (Sertifikat Hak Guna Usaha)',
            'Girik / Letter C',
            'SHSRS (Satuan Rumah Susun)',
        ];

        // -------------------------------------------------------
        // 4. Generate NIK unik secara manual agar tidak tabrakan
        // -------------------------------------------------------
        $nikPool = [];
        while (count($nikPool) < 100) {
            $nik = $faker->numerify('################'); // 16 digit
            if (!in_array($nik, $nikPool)) {
                $nikPool[] = $nik;
            }
        }

        // -------------------------------------------------------
        // 5. Generate 100 nasabah
        // -------------------------------------------------------
        $nasabahBatch  = [];
        $dokumenBatch  = [];
        $lpasBatch     = [];

        for ($i = 0; $i < 100; $i++) {
            $nasabahId = $i + 1; // ID sementara untuk referensi batch

            $nasabahBatch[] = [
                'user_id'       => $userId,
                'nama_nasabah'  => $faker->name(),
                'nik'           => $nikPool[$i],
                'alamat'        => $faker->address(),
                'no_hp'         => '08' . $faker->numerify('#########'),
                'lokasi_lelang' => $faker->randomElement($lokasiLelang),
                'jenis_lelang'  => $faker->randomElement($jenisLelang),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insert nasabah dan ambil ID asli dari DB
        DB::table('nasabah')->insert($nasabahBatch);
        $insertedIds = DB::table('nasabah')
            ->where('user_id', $userId)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        // Build dokumen & lpas batch berdasarkan ID asli
        foreach ($insertedIds as $nasabahId) {
            // 2–4 dokumen per nasabah
            $selectedDokumen = $faker->randomElements($jenisDokumen, rand(2, 4));
            foreach ($selectedDokumen as $dokumen) {
                $dokumenBatch[] = [
                    'nasabah_id'   => $nasabahId,
                    'nama_dokumen' => $dokumen,
                    'link_dokumen' => 'https://drive.google.com/file/d/' . Str::random(33) . '/view?usp=sharing',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            // 1 LPA per nasabah
            $nilaiPasar     = $faker->numberBetween(200_000_000, 5_000_000_000);
            $nilaiLikuidasi = round($nilaiPasar * $faker->randomFloat(2, 0.60, 0.80));
            $nilaiLimit     = round($nilaiLikuidasi * $faker->randomFloat(2, 0.75, 0.95));
            $uangJaminan    = round($nilaiLimit * 0.20);

            $lpasBatch[] = [
                'user_id'         => $userId,
                'nasabah_id'      => $nasabahId,
                'jenis_legalitas' => $faker->randomElement($jenisLegalitas),
                'luas_tanah'      => $faker->randomFloat(2, 50, 2000),
                'luas_bangunan'   => $faker->randomFloat(2, 30, 1500),
                'spek_bangunan'   => $this->randomSpekBangunan($faker),
                'nilai_pasar'     => $nilaiPasar,
                'nilai_likuidasi' => $nilaiLikuidasi,
                'lelang_ke'       => $faker->numberBetween(1, 5),
                'nilai_limit'     => $nilaiLimit,
                'uang_jaminan'    => $uangJaminan,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        DB::table('dokumen_nasabah')->insert($dokumenBatch);
        DB::table('lpas')->insert($lpasBatch);

        $this->command->info('✅ 100 data nasabah, dokumen, dan LPA berhasil di-seed!');
        $this->command->info("   user_id : {$userId} (petugas@silelang.com)");
        $this->command->info('   dokumen : ' . count($dokumenBatch) . ' baris');
        $this->command->info('   lpas    : ' . count($lpasBatch) . ' baris');
    }

    private function randomSpekBangunan(\Faker\Generator $faker): string
    {
        $kondisi  = $faker->randomElement(['Baik', 'Cukup Baik', 'Sedang', 'Butuh Renovasi']);
        $lantai   = $faker->numberBetween(1, 4);
        $kamarTdr = $faker->numberBetween(1, 6);
        $kamarMdi = $faker->numberBetween(1, 4);
        $material = $faker->randomElement(['Beton bertulang', 'Semi permanen', 'Permanen', 'Bata merah plester']);
        $atap     = $faker->randomElement(['Genteng keramik', 'Genteng beton', 'Metal/spandek', 'Asbes']);

        return "Bangunan {$lantai} lantai, {$kamarTdr} kamar tidur, {$kamarMdi} kamar mandi. "
            . "Struktur: {$material}. Atap: {$atap}. Kondisi: {$kondisi}.";
    }
}