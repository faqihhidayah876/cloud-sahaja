<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('original_name');            // nama asli saat upload
            $table->string('stored_name');              // nama unik di disk
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size');         // bytes
            $table->string('extension', 20)->nullable();
            $table->string('share_token', 32)->nullable()->unique();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
