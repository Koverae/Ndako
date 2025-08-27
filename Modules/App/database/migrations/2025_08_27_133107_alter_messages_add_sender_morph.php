<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages','user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->string('sender_type');     // 'user' or 'guest'
            $table->unsignedBigInteger('sender_id');
            $table->index(['sender_type','sender_id']);
        });
    }
    public function down(): void {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['sender_type','sender_id']);
            $table->dropColumn(['sender_type','sender_id']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
