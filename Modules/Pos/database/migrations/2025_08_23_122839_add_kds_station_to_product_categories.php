<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('kds_station')->default('kitchen')->index(); // kitchen|bar|pass|other
        });
    }
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('kds_station');
        });
    }
};
