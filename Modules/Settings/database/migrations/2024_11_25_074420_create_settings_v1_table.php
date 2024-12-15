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
            $table->boolean('default_digest_email')->default(true);
            $table->integer('default_digest_id')->default(1);
            $table->boolean('hr_presence_login')->default(false);
            $table->string('iap_extract_endpoint')->nullable();
            $table->string('product_price_list_settings')->nullable();
            $table->boolean('product_volume_in_cubic_feet')->default(false);
            $table->boolean('product_weight_in_Ibs')->default(false);
            $table->integer('sale_default_confirmation_template')->default(0);
            $table->integer('sale_default_email_template')->default(0);
            $table->string('web_base_url')->nullable();

            // $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            // UoM
            $table->enum('weight', ['kilograms', 'pounds'])->default('kilograms');
            $table->enum('volume', ['cubic_meter', 'cubic_feet'])->default('cubic_meter');
            // Statistics
            $table->boolean('has_digest_email')->default(true);
            // Contact
            $table->boolean('has_quick_find')->default(true);
            // Permissions
            $table->enum('has_customer_account', ['on_invitation', 'free_signup'])->default('free_signup');
            $table->boolean('has_customer_portal')->default(true);
            $table->boolean('has_reset_password')->default(true);
            $table->boolean('has_default_access_right')->default(true);
            $table->boolean('has_import_from_xls')->default(true);
            // Integrations
            $table->boolean('has_mail_plugin')->default(false);
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
            $table->unsignedBigInteger('default_unit_status')->nullable();
            $table->boolean('has_default_numbering')->default(false);
            $table->unsignedBigInteger('default_numbering')->nullable();
            $table->boolean('has_default_utility')->default(false);
            $table->string('default_utility')->nullable();
            $table->boolean('has_floor_mapping')->default(false);
            $table->unsignedBigInteger('floor_mapping')->nullable();
            $table->boolean('has_shared_amenities')->default(false);
            $table->string('shared_amenties')->nullable();
            $table->boolean('has_lease_term')->default(false);
            $table->string('lease_term')->nullable();
            $table->boolean('has_base_rental')->default(true);
            $table->decimal('base_rental', $precision = 12, $scale = 2)->default(0);
            $table->boolean('has_utility_rules')->default(false);
            $table->enum('utility_rule', ['included', 'separate'])->default('included');
            $table->boolean('has_pricelists')->default(false);
            $table->unsignedBigInteger('default_pricelist')->nullable();
            $table->boolean('has_discounts')->default(false);
            // $table->unsignedBigInteger('discounts')->nullable();
            $table->boolean('has_seasonal_discounts')->default(false);
            $table->unsignedBigInteger('seasonal_discount')->nullable();
            $table->boolean('has_default_check_times')->default(false);
            $table->date('default_check_in_time')->nullable();
            $table->date('default_check_out_time')->nullable();
            $table->boolean('has_online_payment')->default(false);
            $table->string('minimum_payment_requested')->default(100);
            $table->boolean('has_lock_confirmed_booking')->default(false);
            $table->boolean('has_pro_formatçinvoice')->default(true);
            $table->boolean('has_overbooking_prevention')->default(true);
            $table->boolean('has_stay_rule_per_unit')->default(true);
            $table->boolean('has_cleaning_frequency')->default(false);
            $table->boolean('has_maintenance_alerts')->default(true);
            $table->boolean('has_housekeeping_staff')->default(true);
            $table->boolean('has_maintenance_requests')->default(true);
            $table->boolean('has_in_room_services')->comment("Enable ordering of room service or add-ons through a guest portal.")->default(false);
            $table->boolean('has_guest_note')->comment("Record specific guest preferences or past feedback for repeat stays.")->default(false);
            // Princing
            $table->boolean('has_discount')->default(false);
            $table->boolean('has_sale_program')->default(false);
            $table->boolean('has_margin')->default(false);
            $table->boolean('has_pricelist_check')->default(true);
            $table->enum('pricelist', ['multiple', 'advanced'])->default('multiple');
            // Channel Manager
            $table->boolean('has_room_mapping')->default(false);
            $table->unsignedBigInteger('default_channel')->nullable();
            $table->enum('rate_sync', ['automatically', 'manually'])->default('automatically');
            $table->boolean('has_cut_off_times')->default(false);
            $table->boolean('has_airbnb_integration')->default(false);
            $table->string("airbnb_api_key")->nullable();
            $table->string("airbnb_oauth_token")->nullable();
            $table->string("airbnb_webhooks_url")->nullable();
            $table->boolean('has_bookingcom_integration')->default(false);
            $table->string("bookingcom_hotel_id")->nullable();
            $table->string("bookingcom_api_key")->nullable();
            $table->string("bookingcom_username")->nullable();
            $table->string("bookingcom_xml_connection")->nullable();
            $table->boolean('has_google_hotel_integration')->default(false);
            $table->string("google_hotel_client_id")->nullable();
            $table->string("google_hotel_api_key")->nullable();
            $table->string("google_hotel_bid")->nullable();
            // Invoicing
            $table->unsignedBigInteger('down_payment')->nullable();
            $table->enum('bill_policy', ['ordered', 'delivered'])->default('delivered');
            $table->boolean('has_way_matching')->default(false);
            $table->boolean('has_automatic_invoice')->default(false);
            // Orders
            $table->boolean('has_order_approval')->default(false);
            $table->decimal('minimum_order_ammount', $precision = 12, $scale = 2)->default(0);
            $table->boolean('has_lock_confirm_order')->default(false);
            $table->boolean('has_warnings')->default(false);
            $table->boolean('has_receipt_reminder')->default(true);
            // Operation
            $table->boolean('has_batch_tranfer')->default(false);
            $table->enum('picking_policy', ['as_soon_as_possible', 'after_done'])->default('as_soon_as_possible');
            $table->boolean('has_quality')->default(false);
            $table->integer('annual_inventory_day')->default(31);
            $table->string('annual_inventory_month')->default('december');
            $table->boolean('has_receipt_report')->default(false);
            // Barcode
            $table->boolean('has_barcode_scanner')->default(false);
            $table->boolean('has_show_qty_to_count')->default(true);
            $table->boolean('has_stock_barcode_database')->default(true);
            $table->unsignedBigInteger('barcode_nomenclature_id')->nullable();
            // Shipping
            $table->boolean('has_shipping_email_confirmation')->default(false);
            $table->boolean('has_shipping_sms_confirmation')->default(false);
            $table->boolean('has_shipping_signature')->default(false);
            $table->boolean('has_delivery_method')->default(false);
            // Traceability
            $table->boolean('has_serial_number')->default(false);
            $table->boolean('has_consignment')->default(false);
            // Valuation
            $table->boolean('has_landed_cost')->default(false);
            // Warehouse
            $table->boolean('has_storage_locations')->default(false);
            $table->boolean('has_storage_categories')->default(false);
            $table->boolean('has_multistep_routes')->default(false);
            // Advanced scheduling
            $table->boolean('has_sale_security_lead_time')->default(false);
            $table->integer('expected_delivery_days')->default(false);
            $table->boolean('has_purchase_security_lead_time')->default(false);
            $table->integer('expected_receipt_days')->default(false);
            $table->integer('days_to_purchase')->default(false);
            //Logisitc
            $table->boolean('has_dropshipping')->default(false);
            // Taxes
            $table->string('fiscal_localization')->default('kenya');
            $table->string('fiscal_country')->default('kenya');
            $table->unsignedBigInteger('default_sales_tax_id')->nullable();
            $table->unsignedBigInteger('default_purchase_tax_id')->nullable();
            $table->enum('rounding_method', ['round_per_line', 'round_globally'])->default('round_per_line');
            // Currencies
            $table->unsignedBigInteger('default_currency_id')->nullable();
            $table->string('default_currency_position')->default('suffix');
            $table->boolean('has_automatic_currency_rate')->default(false);
            $table->unsignedBigInteger('currency_converter_id')->nullable();
            $table->enum('currency_conversion_interval',['manually', 'daily', 'weekly', 'monthly'])->default('manually');
            $table->date('currency_conversion_next_run')->nullable();
            // Customer Invoice
            $table->string('default_sending_options')->nullable();
            $table->boolean('has_customer_address')->default(false);
            $table->boolean('has_taxes_in_company_currency')->default(true);
            $table->boolean('has_customer_invoice_warnings')->default(false);
            $table->boolean('has_cash_rounding')->default(false);
            $table->boolean('has_sale_receipt')->default(false);
            $table->boolean('has_invoice_amount_in_letter')->default(false);
            $table->boolean('has_invoice_authorized_signer')->default(false);
            $table->unsignedBigInteger('default_incoterm_id')->nullable();
            $table->boolean('has_default_terms')->default(false);
            $table->enum('default_terms', ['note', 'link'])->default('note');
            $table->tinyText('default_term_note')->nullable();
            $table->boolean('has_default_credit_limit')->default(false);
            $table->decimal('default_credit_limit', $precision = 12, $scale = 2)->default(0);
            // Customer Payments
            $table->boolean('has_invoice_online_payment')->default(true);
            $table->boolean('has_batch_payments')->default(false);
            $table->boolean('has_invoice_qr_code')->default(false);
            // Vendor Bills
            $table->boolean('has_purchase_receipt')->default(false);
            // Vendor Payments
            // Digitalization
            $table->boolean('has_digitize_document')->default(true);
            $table->enum('digitize_vendor_bills', ['not_digitize', 'digitize_on_demand', 'digitize_automatically'])->default('digitize_automatically');
            $table->enum('digitize_customer_invoices', ['not_digitize', 'digitize_on_demand', 'digitize_automatically'])->default('digitize_on_demand');
            // Analytics
            $table->boolean('has_margin_analysis')->default(false);
            $table->boolean('has_customer_rating')->default(false);
            // Employees
            $table->string('presence_control')->default('on_system');
            $table->boolean('presence_based_on_system')->default(false);
            $table->boolean('has_remote_work')->default(false);
            $table->boolean('presence_based_on_attendance')->default(false);
            $table->boolean('has_advanced_presence_control')->default(false);
            $table->boolean('control_based_on_email_sent')->default(false);
            $table->integer('minimum_email_to_send')->default(0);
            $table->boolean('control_based_on_ip_address')->default(false);
            $table->string('ip_addresses')->nullable();
            $table->boolean('has_skills_management')->default(false);
            // Work Organization
            $table->unsignedBigInteger('default_working_hour_id')->default(false);
            // Employee Update Rights
            $table->boolean('has_employee_editing')->default(false);
            // CRM
            $table->boolean('has_recurring_revenues')->default(false);
            $table->boolean('has_leads')->default(false);
            $table->boolean('has_multi_teams')->default(false);
            $table->boolean('has_rule_based_assignment')->default(false);
            $table->boolean('has_lead_enrichment')->default(true);
            $table->boolean('has_lead_mining')->default(true);
            $table->boolean('has_visit_to_leads')->default(true);
            $table->enum('running', ['manually', 'repeatedly'])->default('manually');
            $table->enum('lead_enrichment', ['on_demand', 'automatically'])->default('automatically');
            // Tasks Management
            $table->boolean('has_recurring_tasks')->default(false);
            $table->boolean('has_task_dependencies')->default(false);
            $table->boolean('has_project_stages')->default(false);
            $table->boolean('has_milestones')->default(false);
            // Time Management
            $table->boolean('has_timesheets')->default(false);
            // Planning
            $table->boolean('has_unassignment')->default(false);
            $table->integer('days_before_unassignment')->default(0);
            $table->boolean('has_project_planning')->default(false);
            //Expenses
            $table->boolean('has_expense_incoming_email')->default(false);
            $table->string('expense_incoming_email')->nullable();
            $table->boolean('has_reimburse_in_payslip')->default(false);
            $table->unsignedBigInteger('default_expense_category_id')->nullable();
            // Expense Accounting
            $table->unsignedBigInteger('expense_journal_id')->nullable(); //Expense: Employee Expense
            $table->string('expense_payment_method')->default('cash');
            //Fiscal Period
            $table->integer('fiscal_year_day')->default(31); //Dernier jour de l'année fiscal
            $table->string('fiscal_year_month')->default('december'); //Dernier mois de l'année fiscal
            $table->boolean('has_multiple_fiscal_year')->default(false);
            $table->date('invoicing_switch_threshold')->nullable(); //Seuil de changement de facturation
            $table->boolean('has_fiscal_dynamic_reports')->default(false);
            // Accounting Analytics
            $table->boolean('has_accounting_analytics')->default(false); //Suivez les coûts et les revenus par projet, département, etc.
            $table->boolean('has_budget_management')->default(false)->comment('Utiliser les budgets pour comparer les revenus et les coûts réels avec ceux attendus');
            // (Storno) Contre-ecriture Accounting
            $table->boolean('has_storno_accounting')->default(false); //Lorsqu'une erreur est commise dans une écriture comptable, au lieu de supprimer l'entrée erronée, on effectue une opération de contre-passation pour annuler les effets de l'erreur.
            // Stock Valuation
            $table->unsignedBigInteger('property_income_account_id')->nullable();
            $table->unsignedBigInteger('property_expense_account_id')->nullable();
            $table->boolean('has_stock_automatic_accounting')->default(false);
            $table->unsignedBigInteger('stock_valuation_account_id')->nullable();
            $table->unsignedBigInteger('stock_journal_id')->nullable();
            $table->unsignedBigInteger('stock_input_account_id')->nullable();
            $table->unsignedBigInteger('stock_output_account_id')->nullable();
            // Manufacturing
            $table->boolean('has_subcontracting')->default(false);
            $table->boolean('has_workshop')->default(true);
            $table->boolean('has_manufacturing_order_unlocked')->default(false);
            $table->boolean('has_production_security_delay')->default(false);
            $table->integer('production_security_delay')->default(0);

            // Peut être agrandi

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
            $table->unsignedBigInteger('company_id');
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
            $table->enum('start_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softdeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_parameters');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('countries');
    }
};
