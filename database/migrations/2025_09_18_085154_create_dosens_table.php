<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('dosens', function (Blueprint $table) {
      $table->id();
      $table->string('nip', 30)->unique()->nullable();   // NIP opsional
      $table->string('nama', 100);
      $table->enum('jenis_kelamin', ['laki-laki','perempuan']);
      $table->string('uid_rfid', 50)->unique();
      $table->boolean('status_uid')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('dosens');
  }
};

