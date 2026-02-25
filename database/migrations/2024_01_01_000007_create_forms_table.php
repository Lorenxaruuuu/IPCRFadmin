<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['Template', 'Guidelines', 'Reference']);
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamp('published_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};