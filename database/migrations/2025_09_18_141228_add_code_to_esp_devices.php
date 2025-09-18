<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('esp_devices', function (Blueprint $t) {
            $t->string('code')->nullable()->unique()->after('id'); // slug teknis (ruangan1/ruangan2)
            $t->index('nama_kelas'); // opsional: bantu pencarian tampilan
        });

        // Backfill awal: map dari nama_kelas → code
        DB::statement("
            UPDATE esp_devices
               SET code = CASE
                    WHEN nama_kelas IN ('SmartClass 1','ruangan1') THEN 'ruangan1'
                    WHEN nama_kelas IN ('SmartClass 2','ruangan2') THEN 'ruangan2'
                    ELSE LOWER(REPLACE(nama_kelas, ' ', '-'))
               END
            WHERE code IS NULL OR code = ''
        ");

        // Jadikan NOT NULL setelah terisi
        Schema::table('esp_devices', function (Blueprint $t) {
            $t->string('code')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('esp_devices', function (Blueprint $t) {
            if (Schema::hasColumn('esp_devices', 'code')) {
                $t->dropUnique(['code']);
                $t->dropColumn('code');
            }
        });
    }
};
