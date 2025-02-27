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
        Schema::create('system_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->enum('account_online_distribution_mode', ['trial', 'production'])->nullable(); //trial or production
            $table->string('account_payment_enable_portal_payment')->nullable();
            $table->boolean('auth_signup_reset_password')->default(true);
            $table->date('database_create_date');
            $table->date('database_expiration_date');
            $table->string('database_expiration_reason')->nullable();
            $table->string('database_secret')->nullable();
            $table->string('database_uuid')->nullable();
            $table->enum('database_type', ['demo', 'test', 'production', 'partnership '])->default('demo');
            $table->enum('database_status', ['active', 'suspended', 'delete', 'blocked'])->default('active');
            $table->boolean('hr_presence_login')->default(false);
            $table->string('iap_extract_endpoint')->nullable();
            $table->string('product_price_list_settings')->nullable();
            $table->string('web_base_url')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', ['id-card', 'passport', 'driver-license']); // ID card, passport, etc.
            $table->string('document_path'); // Path to the uploaded document
            $table->string('selfie_path')->nullable(); // Optional selfie
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Reason if rejected
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            // UoM
            $table->enum('weight', ['kilograms', 'pounds'])->default('kilograms');
            $table->enum('volume', ['cubic_meter', 'cubic_feet'])->default('cubic_meter');

            // Permissions
            $table->enum('has_customer_account', ['on_invitation', 'free_signup'])->default('free_signup');
            $table->boolean('has_customer_portal')->default(true);
            $table->boolean('has_reset_password')->default(true);
            $table->boolean('has_default_access_right')->default(true);
            $table->boolean('has_import_from_xls')->default(true);
            // Integrations
            $table->boolean('has_oauth_authentication')->default(false);
            $table->boolean('has_geo_localization')->default(false);
            $table->enum('geolocation_provider', ['open_street_map', 'google_place_map'])->default('open_street_map');
            $table->boolean('has_google_authentification')->default(false);
            $table->string('google_authentification_client_id')->nullable();
            $table->string('open_street_map_client_id')->nullable();
            $table->boolean('has_linkedin_authentification')->default(false);
            $table->boolean('has_recaptcha')->default(false);
            $table->boolean('has_cloudfare_turnstile')->default(false);
            // Calendar Settings
            $table->boolean('has_outlook_calendar')->default(false);
            $table->boolean('has_google_calendar')->default(false);
                // Outlook calendar settings
                $table->string('outlook_calendar_client_id')->nullable();
                $table->string('outlook_calendar_client_secret')->nullable();
                $table->boolean('outlook_calendar_pause_sync')->default(false);
                // Google calendar settings
                $table->string('google_calendar_client_id')->nullable();
                $table->string('google_calendar_client_secret')->nullable();
                $table->boolean('google_calendar_pause_sync')->default(false);
            // Properties
            $table->boolean('has_default_unit_status')->default(false);
            $table->enum('default_unit_status', ['vacant', 'occupied', 'under-maintenance'])->default('vacant');
            $table->boolean('has_default_numbering')->default(false);
            $table->string('default_numbering')->default('sequential-numbering');
            $table->boolean('has_floor_mapping')->default(false);
            $table->unsignedBigInteger('floor_mapping')->nullable();
            $table->boolean('has_base_rate')->default(true);
            $table->decimal('base_rate', $precision = 12, $scale = 2)->default(0);
            $table->boolean('has_utility_rules')->default(false);
            $table->enum('utility_rule', ['included', 'separate'])->default('included');
            $table->boolean('has_pricelists')->default(false);
            $table->unsignedBigInteger('default_pricelist')->nullable();
            $table->boolean('has_discounts')->default(false);
            $table->boolean('has_seasonal_discounts')->default(false);
            $table->unsignedBigInteger('seasonal_discount')->nullable();
            $table->boolean('has_default_check_times')->default(false);
            $table->date('default_check_in_time')->nullable();
            $table->date('default_check_out_time')->nullable();
            $table->boolean('has_online_payment')->default(false);
            $table->string('minimum_payment_requested')->default(100);
            $table->boolean('has_lock_confirmed_booking')->default(true);
            $table->boolean('has_pro_format_invoice')->default(true);
            $table->boolean('has_overbooking_prevention')->default(true);
            $table->boolean('has_stay_rule_per_unit')->default(true);
            $table->boolean('has_cleaning_frequency')->default(false);
            $table->boolean('has_maintenance_alerts')->default(true);
            $table->boolean('has_housekeeping_staff')->default(true);
            $table->boolean('has_maintenance_requests')->default(true);
            $table->boolean('has_in_room_services')->comment("Enable ordering of room service or add-ons through a guest portal.")->default(false);
            $table->boolean('has_guest_note')->comment("Record specific guest preferences or past feedback for repeat stays.")->default(false);
            // Invoicing
            $table->unsignedBigInteger('down_payment')->nullable();
            $table->boolean('has_automatic_invoice')->default(false);
            // Currencies
            $table->unsignedBigInteger('default_currency_id')->nullable();
            $table->string('default_currency_position')->default('suffix');
            $table->boolean('has_automatic_currency_rate')->default(false);
            $table->unsignedBigInteger('currency_converter_id')->nullable();
            $table->enum('currency_conversion_interval',['manually', 'daily', 'weekly', 'monthly'])->default('manually');
            $table->date('currency_conversion_next_run')->nullable();
            // Customer Invoice
            $table->enum('default_terms', ['note', 'link'])->default('note');
            $table->boolean('has_invoice_online_payment')->default(true);
            $table->boolean('has_invoice_qr_code')->default(false);
            // Time Management
            $table->boolean('has_timesheets')->default(false);
            // Expense Accounting
            $table->unsignedBigInteger('expense_journal_id')->nullable(); //Expense: Employee Expense
            $table->string('expense_payment_method')->default('cash');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });

        // Currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('currency_name');
            $table->string('code')->nullable();
            $table->string('symbol');
            $table->string('thousand_separator')->default('.');
            $table->enum('symbol_position', ['prefix', 'suffix'])->default('suffix');
            $table->string('decimal_separator')->default(',');
            $table->integer('exchange_rate')->default(1);

            $table->timestamps();
        });

        // Languages
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('locale_code')->nullable();
            $table->string('iso_code')->nullable();
            $table->string('url_code')->nullable();
            $table->enum('direction', ['left_to_right', 'right_to_left'])->nullable();
            $table->string('separator_format')->nullable();
            $table->string('decimal_separator')->nullable();
            $table->string('thousand_separator')->nullable();
            $table->string('date_format')->nullable();
            $table->string('time_format')->nullable();
            $table->enum('first_day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('monday'); //First day of the week
            $table->boolean('is_active')->default(false);
            $table->boolean('is_archive')->default(false);
            $table->boolean('is_reference')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Countries
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('common_name');
            $table->string('official_name');
            $table->string('country_code');
            $table->string('currency_code')->nullable();
            $table->string('country_calling_code')->nullable();
            $table->string('googleMaps')->nullable();
            $table->string('openStreetMaps')->nullable();
            $table->string('vat_label')->nullable();
            $table->boolean('zip_required')->default(true);
            $table->boolean('state_required')->default(false);
            $table->string('flag');
            $table->enum('start_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('monday');
            $table->string('capital')->nullable();
            $table->string('region')->nullable(); // Continent (e.g., "Africa")
            $table->string('subregion')->nullable(); // Subregion (e.g., "Eastern Africa")
            $table->text('languages')->nullable(); // Comma-separated languages (e.g., "English, Swahili")

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softdeletes();
        });

        Schema::create('work_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['task', 'situation']); // 'task' or 'situation'
            $table->enum('priority', ['low', 'medium', 'high', 'critical']);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'reported', 'resolved', 'unresolved'])
                  ->default('pending');
            $table->unsignedBigInteger('related_id')->nullable(); // Could link to a related parent (e.g., project, room, etc.)
            $table->unsignedBigInteger('assigned_to')->nullable(); // User for tasks
            $table->unsignedBigInteger('reported_by')->nullable(); // User for situations
            $table->unsignedBigInteger('created_by')->nullable(); // User for situations
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_parameters');
        Schema::dropIfExists('identity_verifications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('work_items');
    }
};
