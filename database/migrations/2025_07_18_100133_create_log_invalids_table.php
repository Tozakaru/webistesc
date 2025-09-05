<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_invalids', function (Blueprint $table) {
            $table->id();
            $table->string('uid_rfid');       // UID dari kartu RFID
            $table->string('reader');         // masuk / keluar
            $table->string('ruangan')->nullable();  // ruangan1 / ruangan2 / atau null
            $table->timestamp('waktu');       // waktu kejadian
            $table->timestamps();             // created_at & updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_invalids');
    }
};
