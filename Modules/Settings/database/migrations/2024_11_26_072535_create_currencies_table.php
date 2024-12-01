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
        Schema::create('currencies', function (Blueprint $table) {
            $table->foreignId('company_id');
            $table->string('currency_name');
            $table->string('code')->nullable();
            $table->string('symbol');
            $table->string('thousand_separator')->default('.');
            $table->enum('symbol_position', ['prefix', 'suffix'])->default('suffix');
            $table->string('decimal_separator')->default(',');
            $table->integer('exchange_rate')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
