<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipcrf_records', function (Blueprint $table) {
            $table->enum('role', ['Teacher', 'Master Teacher', 'Principal', 'Supervisor'])->after('school_year')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ipcrf_records', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
