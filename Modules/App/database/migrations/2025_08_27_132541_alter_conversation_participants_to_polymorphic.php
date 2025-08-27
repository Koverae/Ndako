<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('conversation_participants', function (Blueprint $table) {
            // drop strict FK if you created it earlier
            if (Schema::hasColumn('conversation_participants','user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->string('participant_type'); // 'user' or 'guest'
            $table->unsignedBigInteger('participant_id');
            $table->index(['participant_type','participant_id']);
        });
    }
    public function down(): void {
        Schema::table('conversation_participants', function (Blueprint $table) {
            $table->dropIndex(['participant_type','participant_id']);
            $table->dropColumn(['participant_type','participant_id']);
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
        });
    }
};
