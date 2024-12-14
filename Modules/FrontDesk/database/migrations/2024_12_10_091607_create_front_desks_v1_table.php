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
        Schema::create('front_desks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('active_session_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('has_multiple_employee')->default(false);
            $table->boolean('has_printer_connection')->default(false);
            $table->string('private_key')->nullable();
            $table->enum('status', ['active', 'inactive', 'booked', 'closed', 'on_break'])->default('inactive');
            $table->boolean('is_residence')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('desk_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('setting_id')->nullable();
            $table->unsignedBigInteger('front_desk_id')->nullable();
            // Mobile self-order & Kiosk
            $table->enum('self_ordering', ['disable', 'qr_catalog', 'qr_catalog_ordering', 'kiosk'])->default('qr_catalog');
            // If self-order is qr_menu, qr_menu_ordering or kiosk
            $table->unsignedBigInteger('default_language_id')->nullable();
            $table->unsignedBigInteger('available_language_id')->nullable();
            $table->unsignedBigInteger('default_payment_method_id')->nullable();
            $table->boolean('has_automatically_validate_order')->default(true); //On terminal payment
            $table->boolean('has_maximum_difference_at_closing')->default(false); //On terminal payment
            $table->decimal('maximum_difference_at_closing', $precision = 12, $scale = 2)->default(0.00);
            // Payment Terminal
            $table->boolean('has_stripe_payment_terminal')->default(false);
            $table->boolean('has_paytm_payment_terminal')->default(false);
            // Interface
            $table->boolean('show_property_images')->default(true);
            $table->boolean('show_category_images')->default(true);
            $table->boolean('has_employee_login')->default(true);
            // Accounting
            $table->unsignedBigInteger('defaulft_order_journal_id')->nullable();
            $table->unsignedBigInteger('defaulft_invoice_journal_id')->nullable();
            $table->unsignedBigInteger('default_sales_tax_id')->nullable();
            // Sales
            $table->unsignedBigInteger('desk_pod_id')->nullable();
            // Pricing
            $table->boolean('has_price_control')->default(false);
            $table->boolean('has_line_discounts')->default(false);
            $table->boolean('has_sales_program')->default(false);
            $table->boolean('has_global_discount')->default(false);
            $table->boolean('has_pricer')->default(false);
            $table->enum('product_prices', ['tax-excluded', 'tax-included'])->default('tax-included');
            // Biil & Receipts
            $table->boolean('has_self_service_invoicing')->default(false);
            $table->enum('self_invoicing_print', ['qr-code', 'url', 'qr-code-url'])->default('qr-code');
            $table->boolean('has_qr_code_on_ticket')->default(false);
            $table->boolean('has_unique_code_on_ticket')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('desk_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('front_desk_id')->nullable();
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->string('name');
            $table->string('image_path')->nullable();
            $table->boolean('is_available_online')->default(false);
            $table->boolean('should_be_identified')->default(false);
            $table->enum('integration', ['none', 'terminal'])->default('none');
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('desk_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('front_desk_id')->nullable();
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->decimal('starting_balance', $precision = 12, $scale = 2)->default(0.00);
            $table->decimal('closing_balance', $precision = 12, $scale = 2)->default(0.00);
            $table->enum('status', ['active', 'close_soon', 'closed', 'cancelled'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('front_desks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('desk_settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('desk_payment_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('front_desk_sessions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
