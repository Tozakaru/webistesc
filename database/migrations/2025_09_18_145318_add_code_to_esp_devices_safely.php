<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Tambah kolom 'code' (nullable dulu)
        Schema::table('esp_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('esp_devices', 'code')) {
                $table->string('code', 64)->nullable()->after('id');
            }
            // opsional: index tampilan
            if (!Schema::hasColumn('esp_devices', 'nama_kelas')) {
                // abaikan; hanya supaya file aman jika kolom ini sudah ada
            }
        });

        // 2) Backfill nilai code dari nama_kelas
        //    (map SmartClass 1/2 → ruangan1/ruangan2; sisanya slug dari nama_kelas)
        DB::statement("
            UPDATE esp_devices
            SET code = CASE
                WHEN nama_kelas IN ('SmartClass 1','ruangan1') THEN 'ruangan1'
                WHEN nama_kelas IN ('SmartClass 2','ruangan2') THEN 'ruangan2'
                ELSE LOWER(REPLACE(nama_kelas, ' ', '-'))
            END
            WHERE code IS NULL OR code = ''
        ");

        // 3) Jadikan NOT NULL + UNIQUE (pakai SQL agar tidak perlu doctrine/dbal)
        //    (gunakan IF NOT EXISTS style manual: cek constraint dulu)
        // Pastikan semua sudah terisi agar tidak gagal.
        DB::statement("ALTER TABLE esp_devices MODIFY `code` VARCHAR(64) NOT NULL");

        // Tambah UNIQUE jika belum ada
        $hasUnique = false;
        $indexes = DB::select("SHOW INDEX FROM esp_devices");
        foreach ($indexes as $idx) {
            if ($idx->Key_name === 'esp_devices_code_unique' || ($idx->Non_unique == 0 && $idx->Column_name === 'code')) {
                $hasUnique = true; break;
            }
        }
        if (!$hasUnique) {
            DB::statement("ALTER TABLE esp_devices ADD UNIQUE `esp_devices_code_unique` (`code`)");
        }
    }

    public function down(): void
    {
        // Hapus constraint dan kolom code
        // (abaikan error jika constraint tidak ada)
        try { DB::statement("ALTER TABLE esp_devices DROP INDEX `esp_devices_code_unique`"); } catch (\Throwable $e) {}
        Schema::table('esp_devices', function (Blueprint $table) {
            if (Schema::hasColumn('esp_devices', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
