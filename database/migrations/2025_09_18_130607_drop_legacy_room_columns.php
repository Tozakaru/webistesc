<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('log_aktivitas', 'ruangan')) {
            Schema::table('log_aktivitas', function (Blueprint $t) {
                $t->dropColumn('ruangan');
            });
        }
        if (Schema::hasColumn('esp_commands', 'device_code')) {
            Schema::table('esp_commands', function (Blueprint $t) {
                $t->dropColumn('device_code');
            });
        }
        if (Schema::hasColumn('log_invalids', 'ruangan')) {
            Schema::table('log_invalids', function (Blueprint $t) {
                $t->dropColumn('ruangan');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('log_aktivitas', 'ruangan')) {
            Schema::table('log_aktivitas', function (Blueprint $t) {
                $t->string('ruangan')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('esp_commands', 'device_code')) {
            Schema::table('esp_commands', function (Blueprint $t) {
                $t->string('device_code')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('log_invalids', 'ruangan')) {
            Schema::table('log_invalids', function (Blueprint $t) {
                $t->string('ruangan')->nullable()->after('id');
            });
        }
    }
};
