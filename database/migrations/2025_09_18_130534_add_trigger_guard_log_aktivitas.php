<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // BEFORE INSERT
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_log_aktivitas_bi;
            CREATE TRIGGER trg_log_aktivitas_bi
            BEFORE INSERT ON log_aktivitas
            FOR EACH ROW
            BEGIN
                IF (NEW.mahasiswa_id IS NULL AND NEW.dosen_id IS NULL) OR
                   (NEW.mahasiswa_id IS NOT NULL AND NEW.dosen_id IS NOT NULL) THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Isi tepat satu: mahasiswa_id ATAU dosen_id.';
                END IF;
            END
        ");

        // BEFORE UPDATE
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_log_aktivitas_bu;
            CREATE TRIGGER trg_log_aktivitas_bu
            BEFORE UPDATE ON log_aktivitas
            FOR EACH ROW
            BEGIN
                IF (NEW.mahasiswa_id IS NULL AND NEW.dosen_id IS NULL) OR
                   (NEW.mahasiswa_id IS NOT NULL AND NEW.dosen_id IS NOT NULL) THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Isi tepat satu: mahasiswa_id ATAU dosen_id.';
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_log_aktivitas_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_log_aktivitas_bu;");
    }
};
