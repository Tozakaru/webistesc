<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom FK ke 3 tabel
        if (!Schema::hasColumn('log_aktivitas', 'esp_device_id')) {
            Schema::table('log_aktivitas', function (Blueprint $t) {
                $t->unsignedBigInteger('esp_device_id')->nullable()->after('ruangan');
                $t->index('esp_device_id', 'log_aktivitas_esp_device_id_idx');
            });
        }

        if (!Schema::hasColumn('esp_commands', 'esp_device_id')) {
            Schema::table('esp_commands', function (Blueprint $t) {
                $t->unsignedBigInteger('esp_device_id')->nullable()->after('device_code');
                $t->index('esp_device_id', 'esp_commands_esp_device_id_idx');
            });
        }

        if (!Schema::hasColumn('log_invalids', 'esp_device_id')) {
            Schema::table('log_invalids', function (Blueprint $t) {
                $t->unsignedBigInteger('esp_device_id')->nullable()->after('ruangan');
                $t->index('esp_device_id', 'log_invalids_esp_device_id_idx');
            });
        }

        // Backfill: string → id (ruangan/device_code cocok dengan esp_devices.nama_kelas)
        try {
            DB::statement("
                UPDATE log_aktivitas la
                JOIN esp_devices d ON la.ruangan = d.nama_kelas
                SET la.esp_device_id = d.id
            ");
        } catch (\Throwable $e) { /* ignore if table/column not present yet */ }

        try {
            DB::statement("
                UPDATE esp_commands ec
                JOIN esp_devices d ON ec.device_code = d.nama_kelas
                SET ec.esp_device_id = d.id
            ");
        } catch (\Throwable $e) { /* ignore */ }

        try {
            DB::statement("
                UPDATE log_invalids li
                JOIN esp_devices d ON li.ruangan = d.nama_kelas
                SET li.esp_device_id = d.id
            ");
        } catch (\Throwable $e) { /* ignore */ }

        // Tambah FK (SET NULL jika device dihapus)
        Schema::table('log_aktivitas', function (Blueprint $t) {
            if (!collect(Schema::getConnection()->select("SHOW KEYS FROM log_aktivitas WHERE Key_name = 'log_aktivitas_esp_device_id_fk'"))->count()) {
                $t->foreign('esp_device_id', 'log_aktivitas_esp_device_id_fk')
                  ->references('id')->on('esp_devices')
                  ->onDelete('set null');
            }
        });

        Schema::table('esp_commands', function (Blueprint $t) {
            if (!collect(Schema::getConnection()->select("SHOW KEYS FROM esp_commands WHERE Key_name = 'esp_commands_esp_device_id_fk'"))->count()) {
                $t->foreign('esp_device_id', 'esp_commands_esp_device_id_fk')
                  ->references('id')->on('esp_devices')
                  ->onDelete('set null');
            }
        });

        Schema::table('log_invalids', function (Blueprint $t) {
            if (!collect(Schema::getConnection()->select("SHOW KEYS FROM log_invalids WHERE Key_name = 'log_invalids_esp_device_id_fk'"))->count()) {
                $t->foreign('esp_device_id', 'log_invalids_esp_device_id_fk')
                  ->references('id')->on('esp_devices')
                  ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_aktivitas', function (Blueprint $t) {
            if (Schema::hasColumn('log_aktivitas', 'esp_device_id')) {
                $t->dropForeign('log_aktivitas_esp_device_id_fk');
                $t->dropIndex('log_aktivitas_esp_device_id_idx');
                $t->dropColumn('esp_device_id');
            }
        });

        Schema::table('esp_commands', function (Blueprint $t) {
            if (Schema::hasColumn('esp_commands', 'esp_device_id')) {
                $t->dropForeign('esp_commands_esp_device_id_fk');
                $t->dropIndex('esp_commands_esp_device_id_idx');
                $t->dropColumn('esp_device_id');
            }
        });

        Schema::table('log_invalids', function (Blueprint $t) {
            if (Schema::hasColumn('log_invalids', 'esp_device_id')) {
                $t->dropForeign('log_invalids_esp_device_id_fk');
                $t->dropIndex('log_invalids_esp_device_id_idx');
                $t->dropColumn('esp_device_id');
            }
        });
    }
};
