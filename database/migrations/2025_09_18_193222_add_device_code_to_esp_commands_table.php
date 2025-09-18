<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('esp_commands', function (Blueprint $table) {
            $table->string('device_code', 50)->nullable()->after('id')->index();
        });        
    }
};
