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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name'); // e.g., Airbnb, Booking.com, Google Hotels
            $table->string('avatae')->nullable();
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
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            // $table->unsignedBigInteger('price_list_id')->nullable();
            $table->integer('guests')->default(1);
            $table->mediumText('note')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('paid_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('due_amount', $precision = 12, $scale = 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_method')->default('cash');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', $precision = 12, $scale = 2);
            $table->decimal('left_to_pay', $precision = 12, $scale = 2);
            $table->date('date');
            $table->string('reference');
            $table->string('payment_method');
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
