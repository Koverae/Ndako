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
        // Kredit
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->integer('balance')->default(0);
            $table->string('country_code')->default('KE'); // KE, TZ, UG, RW, ET
            $table->timestamps();
        });

        // Kredit Transaction(Log)
        Schema::create('kredit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kredit_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreignId('user_id')->nullable(); // Action By
            $table->string('reference')->nullable();
            $table->enum('type', ['debit', 'credit'])->default('debit'); // debit, credit
            $table->string('action'); // sms_sent, booking_reminder, etc.
            $table->integer('amount');
            $table->text('meta')->nullable(); // JSON
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., booking.created, payment.received
            $table->json('data'); // Store notification details
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredits');
        Schema::dropIfExists('kredit_transactions');
        Schema::dropIfExists('notifications');
    }
};
