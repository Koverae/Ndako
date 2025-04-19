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
        // Property Types
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('property_type', ['single', 'multi', 'custom'])->default('multi');
            $table->enum('property_type_group', ['residential', 'commercial', 'hospitality', 'mixed'])->default('hospitality'); // Optional categorization
            $table->json('attributes')->nullable(); // Customizable attributes
            $table->json('default_settings')->nullable(); // Default attribute values

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Properties
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_type_id')->nullable();
            $table->enum('invoicing_type', ['rental', 'rate'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('images')->nullable(); // Images
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address')->nullable();
            // $table->json('amenities')->nullable();
            $table->enum('status', ['active', 'inactive', 'under-maintenance'])->nullable();

            // Required Documents
            $table->json('required_documents')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Floors
        Schema::create('property_floors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->string('name')->comment('e.g., "Ground Floor", "First Floor"');
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Units Types
        Schema::create('property_unit_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('pricing_id')->nullable();
            $table->string('name')->comment("e.g., 'Premium Room', 'Cluster Room', 'Twin Room'");
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->text('images')->nullable(); // Images
            $table->integer('capacity')->default(1);
            $table->string('size')->nullable();
            $table->json('unit_features')->nullable();
            $table->json('unit_utilities')->nullable();
            $table->boolean('is_available')->default(true);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Unit Type Pricings
        Schema::create('property_unit_type_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('lease_term_id')->nullable();
            $table->string('name')->nullable()->comment('e.g.,"Deluxe Room Nightly", "Premium Room Price", "Twin Room Price"');
            $table->decimal('price', 12, 2);
            $table->decimal('discounted_price', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_per_night')->default(false);
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
        // Units
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_unit_type_id');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('images')->nullable(); // Images
            $table->integer('capacity')->default(1);
            $table->json('default_setttings')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_cleaned')->default(true);
            $table->string('status')->default('vacant');
            $table->timestamp('last_cleaned_at')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Unit Statuses
        Schema::create('unit_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->comment('e.g., "Occupied", "Vacant", "Under Maintenance"');
            $table->text('description')->nullable();
            $table->boolean('is_housekeeping')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Utilities
        Schema::create('utilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_included')->default(true)->comment("e.g., cost per kWh, cubic meter, or flat rate");
            $table->enum('billing_cycle', ['weekly', 'monthly', 'quarterly ', 'yearly'])->default('monthly');
            $table->decimal('price_per_unit', $precision = 12, $scale = 2)->default(0);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Amenities
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('images')->nullable(); // Images

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Features
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('category')->nullable(); // Optional: Categorize features

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Properties Amenities
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('amenity_id');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // Properties Utilities
        Schema::create('property_utilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->unsignedBigInteger('utility_id');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // Features
        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->text('images')->nullable(); // Images

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });

        // Extra Services
        Schema::create('extra_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->string('name')->comment('e.g., "Room Cleaning", "Breakfast"');
            $table->text('description')->nullable();
            $table->string('category')->nullable()->comment('e.g. "Food", "Laundry", "Housekeeping"');
            $table->decimal('price', 12, 2);
            $table->boolean('auto_approve')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('guest_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('total_amount', 12, 2);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('guest_service_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_service_id')->nullable();
            $table->unsignedBigInteger('extra_service_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // Tenants
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->enum('type', ['individual', 'enterprise'])->default('individual');
            $table->date('birthday')->nullable();

            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('monthly_income')->default(0);
            $table->unsignedBigInteger('language_id')->nullable();

            // Address
            $table->string('street')->nullable();
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();

            // Contact Info
            $table->enum('identification_type', ['id-card', 'passport', 'driving-license', 'resident-permit'])->default('id-card');
            $table->string('identification')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tags')->nullable();
            $table->string('kin_name')->nullable();
            $table->string('kin_email')->nullable();
            $table->string('kin_address')->nullable();
            $table->string('kin_phone')->nullable();

            // Individual
            $table->string('job')->nullable();
            $table->boolean('has_receipt_reminder')->default(false);
            $table->integer('days_before')->default(0);

            // Lease Details
            $table->boolean('is_active')->default(true); // Indicates if they have an active lease
            $table->unsignedBigInteger('lease_id')->nullable(); // Link to the lease

            // Misc
            $table->string('companyID')->nullable();
            $table->string('reference')->nullable();
            $table->mediumText('note')->nullable();
            $table->enum('status', ['draft', 'process', 'approved'])->default('approved');

            $table->json('documents')->nullable(); // Stores uploaded documents
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });

        // LeaseTerm
        Schema::create('lease_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->comment('e.g., "nightly", "weekly", "monthly", "annually"');
            $table->text('description')->nullable();
            $table->integer('duration_in_days')->default(0);
            $table->integer('duration_in_hours')->default(0);
            $table->boolean('is_default')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Lease
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // The company managing the property
            $table->unsignedBigInteger('property_id'); // The rental unit
            $table->unsignedBigInteger('property_unit_id'); // The rental unit
            $table->unsignedBigInteger('tenant_id'); // The tenant occupying the unit
            $table->unsignedBigInteger('agent_id')->nullable(); // Agent managing the lease (if applicable)
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            // Lease Term Relationship
            $table->unsignedBigInteger('lease_term_id')->nullable(); // Link to lease_terms table

            // Lease Details
            $table->string('reference')->nullable(); // Unique lease reference number
            $table->date('start_date'); // Lease start date
            $table->date('end_date'); // Lease end date

            // Financials
            $table->decimal('rent_amount', 12, 2)->default(0); // Monthly/periodic rent amount
            $table->decimal('deposit_amount', 12, 2)->default(0); // Security deposit
            // $table->decimal('paid_amount', 12, 2)->default(0); // Amount already paid
            // $table->decimal('due_amount', 12, 2)->default(0); // Remaining unpaid rent
            $table->decimal('penalty_amount', 12, 2)->default(0); // Late payment penalties
            $table->decimal('refund_amount', 12, 2)->default(0); // Refundable amount (if any)

            // Payment Details
            $table->enum('invoice_status', ['not_invoiced', 'partial', 'invoiced'])->default('not_invoiced');

            // Lease Status
            $table->enum('status', ['pending', 'active', 'expired', 'terminated'])->default('pending');

            // Lease Agreement Attachments
            $table->string('agreement_file')->nullable(); // Path to lease agreement document

            $table->timestamps();
            $table->softDeletes();
        });
        // Lease Invoice
        Schema::create('lease_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('lease_id')->nullable(); // Linked to a lease
            $table->date('date')->nullable(); // Invoice date
            $table->string('code')->nullable(); //2025_04 or April 2025
            $table->string('reference'); // Unique invoice reference
            $table->unsignedBigInteger('tenant_id')->nullable(); // Linked to tenant

            $table->string('payment_reference')->nullable(); // Payment tracking reference
            $table->date('due_date')->nullable(); // Invoice due date

            // Invoice Breakdown
            $table->decimal('tax_percentage', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_percentage', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            $table->enum('status', ['draft', 'posted', 'canceled'])->default('draft');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->string('tenant_reference')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();

            // Accounting
            $table->string('auto_post')->nullable();
            $table->boolean('to_checked')->default(false);

            $table->string('terms')->nullable(); // Payment terms
            $table->timestamps();
            $table->softDeletes();
        });
        // Lease Payments
        Schema::create('lease_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('lease_invoice_id'); // Linked to an invoice
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->string('reference'); // Unique payment reference
            $table->string('transaction_id')->nullable(); // Transaction tracking ID
            $table->decimal('amount', 12, 2)->default(0); // Payment amount
            $table->decimal('due_amount', 12, 2)->default(0); // Remaining due amount
            $table->date('date'); // Payment date
            $table->string('payment_method'); // E.g., M-Pesa, bank transfer, cash
            $table->enum('type', ['debit', 'credit'])->default('credit'); // Credit = Rent received
            $table->enum('status', ['posted', 'pending', 'failed'])->default('pending'); // Payment status
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
        Schema::table('property_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_floors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_unit_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_unit_type_pricings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('unit_statuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('utilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_utilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_amenities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('features', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('unit_pricelists', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('leases', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('lease_invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('lease_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('lease_terms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
