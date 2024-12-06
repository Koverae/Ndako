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
            $table->string('name'); // e.g., Airbnb, Booking.com, Google Hotels
            $table->string('api_endpoint')->nullable(); // Base API URL
            $table->json('credentials')->nullable(); // API credentials in JSON
            $table->boolean('is_active')->default(true); // Channel status
            $table->timestamp('last_sync_date')->default(true); // Last Sync Date/Time
            $table->timestamps();
        });
        // Channel Inventory
        Schema::create('channel_inventories', function (Blueprint $table) {
            $table->id();
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
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('sync_type'); // 'availability', 'price', 'full_sync'
            $table->json('request_payload'); // Data sent to the channel
            $table->json('response_payload'); // Response received from the channel
            $table->string('status'); // 'success', 'error'
            $table->text('error_message')->nullable(); // Error details if any
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
