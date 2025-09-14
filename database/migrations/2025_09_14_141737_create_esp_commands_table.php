<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('esp_commands', function (Blueprint $table) {
            $table->id();
            $table->string('device_code', 50)->index(); // ruangan1 / ruangan2
            $table->string('command', 50);              // FORCE_OPEN, dll.
            $table->json('payload')->nullable();        // {"duration_ms":3000}
            $table->enum('status', ['pending','sent','done','failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedSmallInteger('retry_count')->default(0);
            $table->foreignId('issued_by')->constrained('users');
            $table->timestamps();

            $table->index(['device_code','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esp_commands');
    }
};
