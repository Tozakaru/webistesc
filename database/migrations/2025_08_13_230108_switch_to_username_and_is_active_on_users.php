<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom baru sementara nullable agar bisa diisi dulu
            $table->string('username')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('password');
        });

        // Isi username dari prefix email (sebelum '@')
        // Catatan: fungsi SUBSTRING_INDEX hanya untuk MySQL/MariaDB
        DB::statement("
            UPDATE users
            SET username = CASE
                WHEN email LIKE '%@%' THEN SUBSTRING_INDEX(email, '@', 1)
                ELSE CONCAT('user', id)
            END
        ");

        // Tangani duplikat username: tambahkan '_{id}'
        // (MySQL tidak mudah cek duplikat per-row di UPDATE sederhana; lakukan 2 langkah)
        $dupes = DB::table('users')
            ->select('username')
            ->groupBy('username')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('username');

        foreach ($dupes as $uname) {
            $rows = DB::table('users')->where('username', $uname)->orderBy('id')->get();
            $first = true;
            foreach ($rows as $r) {
                if ($first) { $first = false; continue; }
                DB::table('users')->where('id', $r->id)->update([
                    'username' => $r->username . '_' . $r->id
                ]);
            }
        }

        // Jadikan NOT NULL + UNIQUE
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
        });

        // Drop kolom lama yang tidak dipakai lagi
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->enum('status', ['submitted','approved','rejected'])->default('submitted')->after('password');
            $table->dropColumn('username');
            $table->dropColumn('is_active');
        });
    }
};
