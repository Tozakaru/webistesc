<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('log_aktivitas', function (Blueprint $table) {
      // kalau FK lama ada, drop dulu (nama default: log_aktivitas_mahasiswa_id_foreign)
      try { $table->dropForeign(['mahasiswa_id']); } catch (\Throwable $e) {}

      $table->unsignedBigInteger('mahasiswa_id')->nullable()->change();
      $table->unsignedBigInteger('dosen_id')->nullable()->after('mahasiswa_id');

      $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
      $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::table('log_aktivitas', function (Blueprint $table) {
      $table->dropForeign(['mahasiswa_id']);
      $table->dropForeign(['dosen_id']);
      $table->dropColumn('dosen_id');
      $table->unsignedBigInteger('mahasiswa_id')->nullable(false)->change();
      $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
    });
  }
};

