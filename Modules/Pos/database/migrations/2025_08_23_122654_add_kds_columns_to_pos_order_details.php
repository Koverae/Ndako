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
        Schema::table('pos_order_details', function (Blueprint $table) {
            $table->string('kds_status')->default('queued')->index();   // queued|preparing|ready|delivered|void
            $table->string('kds_station')->default('kitchen')->index(); // kitchen|bar|pass|other
            $table->foreignId('kds_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('kds_preparing_at')->nullable();
            $table->timestamp('kds_ready_at')->nullable();
            $table->timestamp('kds_delivered_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_order_details', function (Blueprint $table) {
            $table->dropColumn([
                'kds_status','kds_station','kds_user_id',
                'kds_preparing_at','kds_ready_at','kds_delivered_at'
            ]);
        });
    }
};
