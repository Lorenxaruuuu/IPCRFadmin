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
        Schema::table('notices', function (Blueprint $table) {
            // drop existing FK then make column nullable
            $table->dropForeign(['posted_by']);
            $table->unsignedBigInteger('posted_by')->nullable()->change();
            $table->foreign('posted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['posted_by']);
            $table->unsignedBigInteger('posted_by')->change();
            $table->foreign('posted_by')->references('id')->on('users');
        });
    }
};