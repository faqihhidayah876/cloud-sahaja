<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('user_id')
                  ->constrained('files')->onDelete('cascade');
            $table->boolean('is_folder')->default(false)->after('is_public');
            $table->boolean('is_starred')->default(false)->after('is_folder');
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
            
            $table->index(['user_id', 'deleted_at']);
            $table->index(['user_id', 'is_starred']);
            $table->index(['user_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'is_folder', 'is_starred', 'deleted_at']);
        });
    }
};
