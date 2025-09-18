<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'dosen_id')) {
                $t->unsignedBigInteger('dosen_id')->nullable()->after('id');
                $t->foreign('dosen_id', 'users_dosen_id_fk')
                  ->references('id')->on('dosens')
                  ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (Schema::hasColumn('users', 'dosen_id')) {
                $t->dropForeign('users_dosen_id_fk');
                $t->dropColumn('dosen_id');
            }
        });
    }
};
