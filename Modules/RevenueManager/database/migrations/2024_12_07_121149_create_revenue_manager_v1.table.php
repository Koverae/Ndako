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
        // Fiscal Package
        Schema::create('fiscal_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('country_code')->nullable();
            $table->boolean('is_active')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
        // Payment Terms
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->nullable();
            $table->boolean('has_early_discount')->default(false);
            $table->integer('discount_percentage')->default(2);
            $table->integer('in_advance_day')->default(7);
            $table->tinyText('note')->nullable();
            $table->enum('reduced_tax',['on_early_payment', 'always', 'never'])->default('on_early_payment');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('payment_due_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('payment_term_id');
            $table->integer('due_amount')->default(100);
            $table->enum('due_format', ['percent', 'fixed'])->default('percent');
            $table->integer('after')->nullable(); //Days after which the amount is to be paid
            $table->enum('after_date', ['after_invoice_date', 'after_end_of_the_month', 'after_end_of_the_next_month', 'end_of_the_month_of'])->default('after_invoice_date'); //Days after which the amount is to be paid
            $table->integer('month')->default(0);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
        // Reminder Levels
        Schema::create('follow_up_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('description')->nullable();
            $table->integer('days_after_due')->default(0);
            // Actions
            $table->boolean('has_send_email')->default(true);
            $table->boolean('has_send_sms')->default(false);
            $table->unsignedBigInteger('email_template_id')->nullable();
            $table->unsignedBigInteger('sms_template_id')->nullable();
            // Options
            $table->boolean('has_automatic')->default(false);
            $table->boolean('has_attached_invoices')->default(true);
            $table->string('reminder_followers')->nullable();
            // Activity
            $table->boolean('has_scheduled_activity')->default(false);
            $table->unsignedBigInteger('activity_type_id')->nullable();
            $table->enum('responsible',['follow-up responsible', 'salesperson', 'account-manager'])->default('follow-up responsible');
            $table->tinyText('activity_note')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
        // Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('country_code')->nullable(); // e.g., 'KE' for Kenya
            $table->unsignedBigInteger('tax_group_id')->nullable();
            $table->string('name');
            $table->string('wording_on_invoice')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('legal_note')->nullable();
            $table->enum('computation_type', ['fixed', 'percentage', 'percentage_tax_included', 'group'])->default('percentage');
            $table->enum('type', ['sales', 'purchases', 'misc', 'none'])->default('none');
            $table->enum('tax_scope', ['goods', 'interventions'])->nullable();
            $table->decimal('rate', 5, 2)->default(0); // e.g., 16%, 5%, etc.
            $table->decimal('amount', $precision = 12, $scale = 2)->default(0);
            $table->enum('application_type', ['inclusive', 'exclusive'])->default('exclusive');
            $table->json('applicable_to')->nullable(); // Categories for tax application
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        // Customer Invoice
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('reference');
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('payment_reference')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_term')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('journal_id')->nullable();

            // Invoice Ligne
            $table->decimal('tax_percentage', $precision = 12, $scale = 2)->default(0);
            $table->decimal('tax_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('discount_percentage', $precision = 12, $scale = 2)->default(0);
            $table->decimal('discount_amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('shipping_amount', $precision = 12, $scale = 2)->default(0);
            $table->date('shipping_date')->nullable();
            $table->decimal('total_amount', $precision = 12, $scale = 2);
            $table->decimal('paid_amount', $precision = 12, $scale = 2);
            $table->decimal('due_amount', $precision = 12, $scale = 2);
            $table->string('status');
            $table->string('payment_status')->nullable();

            // Journal Items

            // Invoice
            $table->string('customer_reference')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreignId('sales_team_id')->nullable();

            // Accounting
            $table->unsignedBigInteger('incoterm_id')->nullable();
            $table->string('fiscal_position')->nullable();
            $table->string('auto_post')->nullable();
            $table->boolean('to_checked')->defalut(false);

            $table->string('terms')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('customer_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_invoice_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('label');
            $table->decimal('quantity', $precision = 12, $scale = 2);
            $table->decimal('price', $precision = 12, $scale = 2);
            $table->decimal('unit_price', $precision = 12, $scale = 2);
            $table->decimal('sub_total', $precision = 12, $scale = 2);
            $table->decimal('product_discount_amount', $precision = 12, $scale = 2);
            $table->string('product_discount_type')->default('fixed');
            $table->decimal('product_tax_amount', $precision = 12, $scale = 2);

            $table->timestamps();
            $table->softDeletes();
        });
        // Customer Invoice Payment
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_invoice_id');
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->decimal('amount', $precision = 12, $scale = 2)->default(0);
            $table->decimal('due_amount', $precision = 12, $scale = 2)->default(0);
            $table->date('date');
            $table->string('reference')->nullable();
            $table->string('payment_method');
            $table->text('note')->nullable();
            $table->string('status');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
        // Journals
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->enum('type', ['sale','purchase', 'cash', 'bank', 'm-pesa', 'paystack', 'miscellaneous'])->nullable();
            $table->string('short_code');
            $table->unsignedBigInteger('default_account_id')->nullable();
            // Accounting Informations
                // Sale
                $table->unsignedBigInteger('default_income_account_id')->nullable();
                $table->boolean('dedicated_credit_sequence')->default(false);
                // Purchase
                $table->unsignedBigInteger('default_expense_account_id')->nullable();
                //Cash
                $table->unsignedBigInteger('cash_account_id')->nullable();
                $table->unsignedBigInteger('profit_account_id')->nullable();
                $table->unsignedBigInteger('loss_account_id')->nullable();
                $table->boolean('dedicated_payment_sequence')->default(false);
                // Bank
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->string('account_number')->nullable();
            //Advanced Settings

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });

        // Journal Entries
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('journal_entry');
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->string('label');
            $table->decimal('debit', $precision = 12, $scale = 2)->default(0);
            $table->decimal('credit', $precision = 12, $scale = 2)->default(0);
            $table->decimal('balance', $precision = 12, $scale = 2)->default(0);
            $table->boolean('is_matching')->default(false);
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('posted');

            $table->timestamps();
            $table->softDeletes();
        });

        // Expenses
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->enum('type', ['operational', 'staffing', 'guest_services', 'marketing', 'property', 'technology', 'financial_legal', 'inventory', 'capital', 'miscellaneous'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('expense_category_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('title');
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_recurrent')->default(false);
            $table->enum('recurrence', ['daily', 'weekly', 'monthly', 'quaterly', 'yearly'])->nullable();
            $table->date('date')->nullable();
            $table->date('next_due_at')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('paid');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fiscal_packages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('payment_due_terms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('follow_up_levels', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('customer_invoice_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('journals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
