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
        // Guest
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            // Address
            $table->string('street')->nullable();
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('zip')->nullable();
            // Contact Info
            $table->enum('identity_proof', ['id_card', 'passport'])->default(('passport'));
            $table->string('identity')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tags')->nullable();
            // Individual
            $table->string('job')->nullable();
            $table->boolean('has_receipt_reminder')->default(false);
            $table->integer('days_before')->default(0);
            // MISC
            $table->string('companyID')->nullable();
            $table->string('reference')->nullable();
            $table->mediumText('note')->nullable();
            $table->enum('type', ['individual', 'company', 'agent'])->default('individual');
            $table->boolean('status')->default(true);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name'); // e.g., Airbnb, Booking.com, Google Hotels
            $table->string('avatar')->nullable();
            $table->string('api_endpoint')->nullable(); // Base API URL
            $table->json('credentials')->nullable(); // API credentials in JSON
            $table->enum('integration_status', ['connected', 'disconnected'])->default('disconnected');
            $table->boolean('is_active')->default(true); // Channel status
            $table->timestamp('last_sync_date')->nullable(); // Last Sync Date/Time
            $table->timestamps();
        });
        // Channel Inventory
        Schema::create('channel_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable(); // Your properties table
            $table->date('date'); // Date of inventory record
            $table->integer('available_rooms'); // Number of available rooms
            $table->decimal('price', 10, 2); // Price per room
            $table->timestamps();
        });
        // Channel Sync Logs
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('sync_type'); // 'availability', 'price', 'full_sync'
            $table->json('request_payload'); // Data sent to the channel
            $table->json('response_payload'); // Response received from the channel
            $table->string('status'); // 'success', 'error'
            $table->text('error_message')->nullable(); // Error details if any
            $table->timestamps();
        });
        // Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            // $table->unsignedBigInteger('price_list_id')->nullable();
            $table->string('reference')->nullable();
            $table->integer('guests')->default(1);
            $table->mediumText('note')->nullable();
            $table->datetime('check_in');
            $table->datetime('check_out');
            $table->decimal('unit_price', $precision = 12, $scale = 2)->default(0);
            $table->decimal('total_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('paid_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('due_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('refund_amount', $precision = 12, $scale = 2)->default(0);
            $table->enum('payment_type', ['debit', 'credit'])->default('credit');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_method')->default('cash');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            $table->string('source')->default('direct-booking');
            $table->enum('invoice_status', ['not_invoiced', 'partial', 'invoiced'])->default('not_invoiced');

            // Extended Hours
            $table->boolean('early_check_in')->default(false);
            $table->boolean('late_check_out')->default(false);
            $table->integer('extra_hours')->nullable();
            $table->decimal('extra_charge', 8, 2)->default(0);

            // Additional fields for check-in/check-out process
            $table->datetime('actual_check_in')->nullable(); // Exact time of check-in
            $table->datetime('actual_check_out')->nullable(); // Exact time of check-out
            $table->enum('check_in_status', ['pending', 'checked_in'])->default('pending'); // Track check-in status
            $table->enum('check_out_status', ['pending', 'checked_out'])->default('pending'); // Track check-out status

            $table->timestamps();
            $table->softDeletes();
        });

        // Customer Invoice
        Schema::create('booking_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->date('date')->nullable();
            $table->string('reference');
            $table->unsignedBigInteger('guest_id')->nullable();

            $table->string('payment_reference')->nullable();
            $table->date('due_date')->nullable();

            // Invoice Ligne
            $table->decimal('tax_percentage', $precision = 12, $scale = 2)->default(0);
            $table->decimal('tax_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('discount_percentage', $precision = 12, $scale = 2)->default(0);
            $table->decimal('discount_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('total_amount', $precision = 12, $scale = 2);
            $table->decimal('paid_amount', $precision = 12, $scale = 2);
            $table->decimal('due_amount', $precision = 12, $scale = 2);
            $table->enum('status', ['draft', 'posted', 'canceled'])->default('draft');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->string('guest_reference')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();

            // Accounting
            $table->string('auto_post')->nullable();
            $table->boolean('to_checked')->default(false);

            $table->string('terms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('booking_invoice_id');
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->string('reference');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('due_amount', $precision = 12, $scale = 2)->default(0);
            $table->date('date');
            $table->string('payment_method');
            $table->enum('type', ['debit', 'credit'])->default('credit');
            $table->enum('status', ['posted', 'pending'])->default('pending');
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('channels');
        Schema::dropIfExists('channel_inventories');
        Schema::dropIfExists('sync_logs');
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
