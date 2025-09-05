<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // jika sebelumnya ada foreign key
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();

            // jika sebelumnya ada foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
