<?php
namespace Modules\RevenueManager\Handlers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\App\Handlers\AppHandler;
use Modules\App\Services\Wallet\KreditService;
use Modules\RevenueManager\Models\Accounting\Journal;
use Modules\RevenueManager\Models\Expenses\ExpenseCategory;
use Modules\RevenueManager\Models\Payment\FollowUpLevel;
use Modules\RevenueManager\Models\Payment\PaymentDueTerm;
use Modules\RevenueManager\Models\Payment\PaymentTerm;
use Modules\RevenueManager\Models\Tax;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Models\System\Setting;
use Modules\Settings\Models\SystemParameter;
use Ramsey\Uuid\Uuid;

class RevenueManagerAppHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'revenue-manager';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        $this->createPaymentTerms($company);
        $this->createAccountingJournals($company);
        $this->createExpenseCategories($company);
        $this->createFollowUpLevels($company);
        $this->createWallet($company);

    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    /**
     * Create Wallet.
     *
     * @param int $companyId
     */
    public function createWallet($companyId){
        $company = Company::find($companyId);

        $kreditService = new KreditService;
        $kreditService->topUp($company->team->id, 10); // The starting kredits is defined by the plan selected

    }

    /**
     * Install default company settings and system parameters.
     *
     * @param Company $company
     */
    private function createPaymentTerms(int $companyId): void
    {
        // Payment terms array
        $paymentTerms = [
            [
                'name' => 'Immediate Payment',
                'after' => 0,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => '14 Days',
                'after' => 14,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => '21 Days',
                'after' => 21,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => '30 Days',
                'after' => 30,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => '45 Days',
                'after' => 45,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => 'End of Following Month',
                'after' => null,
                'after_date' => 'after_end_of_the_next_month',
            ],
            [
                'name' => '10 Days after End of Next Month',
                'after' => 10,
                'after_date' => 'after_end_of_the_next_month',
            ],
            [
                'name' => '30% Now, Balance 60 Days',
                'due_amounts' => [
                    [
                        'due_amount' => '30',
                        'due_format' => 'percent',
                        'after' => 0,
                        'after_date' => 'after_invoice_date',
                    ],
                    [
                        'due_amount' => '70',
                        'due_format' => 'percent',
                        'after' => 60,
                        'after_date' => 'after_invoice_date',
                    ]
                ]
            ],
            [
                'name' => '2/7 Net 30',
                'has_early_discount' => true,
                'discount_percentage' => 2,
                'in_advance_day' => 7,
                'after' => 30,
                'after_date' => 'after_invoice_date',
            ],
            [
                'name' => '90 days, on the 10th',
                'after' => 90,
                'month' => 10,
                'after_date' => 'end_of_the_month_of',
            ],
        ];

        // Loop through payment terms and insert into the payment_terms and payment_due_terms tables
        foreach ($paymentTerms as $term) {
            // Insert into payment_terms table
            $paymentTerm = PaymentTerm::create([
                'company_id' => $companyId,
                'name' => $term['name'],
                'has_early_discount' => $term['has_early_discount'] ?? false,
                'discount_percentage' => $term['discount_percentage'] ?? 2,
                'in_advance_day' => $term['in_advance_day'] ?? 7,
                'note' => 'Payment terms: '. $term['name'],
                'reduced_tax' => $term['reduced_tax'] ?? 'on_early_payment',

            ]);
            $paymentTerm->save();

            // Insert into payment_due_terms table for terms that have due_amounts defined
            if (isset($term['due_amounts'])) {
                foreach ($term['due_amounts'] as $dueTerm) {
                    PaymentDueTerm::create([
                        'company_id' => $companyId,
                        'payment_term_id' => $paymentTerm->id,
                        'due_amount' => $dueTerm['due_amount'],
                        'due_format' => $dueTerm['due_format'],
                        'after' => $dueTerm['after'],
                        'after_date' => $dueTerm['after_date'],
                    ]);
                }
            } else {
                // Insert the main payment due term for single payment term
                PaymentDueTerm::create([
                    'company_id' => $companyId,
                    'payment_term_id' => $paymentTerm->id,
                    'due_amount' => 100,
                    'due_format' => 'percent',
                    'after' => $term['after'] ?? null,
                    'after_date' => $term['after_date'] ?? 'after_invoice_date',
                    'month' => $term['month'] ?? 0,
                ]);
            }
        }

    }

    /**
     * Install Follow-up Levels.
     *
     * @param int $companyId
     */
    public function createFollowUpLevels($companyId) : void
    {
        $followUpLevels = [
            [
                'description' => '14 Days',
                'days_after_due' => 2,
            ],
        ];

        foreach($followUpLevels as $level){
            FollowUpLevel::create(array_merge(['company_id' => $companyId], $level));
        }
    }

    /**
     * Install specific dashboards for invoicing.
     *
     * @param int $companyId
     */
    private function createAccountingJournals(int $companyId): void
    {
        $journals = [
            [
                'company_id' => $companyId,
                'name' => 'Customer Invoices',
                'type' => 'sale',
                'short_code' => 'INV'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Supplier Invoices',
                'type' => 'purchase',
                'short_code' => 'BILL'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Miscellaneous Operations',
                'type' => 'miscellaneous',
                'short_code' => 'MISC'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Exchange Rate Difference',
                'type' => 'miscellaneous',
                'short_code' => 'EXCH'
            ],
            [
                'company_id' => $companyId,
                'name' => 'VAT on Payments',
                'type' => 'miscellaneous',
                'short_code' => 'CABA'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Bank',
                'type' => 'bank',
                'short_code' => 'BNK1'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Cash',
                'type' => 'cash',
                'short_code' => 'CSH1'
            ],
            [
                'company_id' => $companyId,
                'name' => 'M-Pesa',
                'type' => 'm-pesa',
                'short_code' => 'MPESA'
            ],
            [
                'company_id' => $companyId,
                'name' => 'Paystack',
                'type' => 'paystack',
                'short_code' => 'PSTACK'
            ],
        ];
        foreach($journals as $journal){
            Journal::create($journal);
        }
    }

    // Create expenses categories
    private function createExpenseCategories(int $companyId){

        $expenseCategories = [
            'operational' => [
                ['name' => 'Utilities (Electricity, Water, Internet)', 'description' => 'Monthly bills for electricity, water supply, internet and phone services.'],
                ['name' => 'Cleaning & Laundry', 'description' => 'Costs for room cleaning, linen washing, and external laundry services.'],
                ['name' => 'Maintenance & Repairs', 'description' => 'General repairs, equipment maintenance, plumbing, and electrical work.'],
                ['name' => 'Waste Management', 'description' => 'Garbage collection and disposal services.'],
                ['name' => 'Security Services', 'description' => 'Security staff, surveillance systems, and third-party security providers.'],
                ['name' => 'Transportation / Fuel', 'description' => 'Fuel and transport expenses for logistics, errands or guest transfers.'],
                ['name' => 'Office Supplies', 'description' => 'Stationery, paper, pens, printing materials, and related items.'],
            ],

            'staffing' => [
                ['name' => 'Staff Salaries / Payroll', 'description' => 'Total salary payments to all employees.'],
                ['name' => 'Overtime & Bonuses', 'description' => 'Extra payments for staff overtime or performance bonuses.'],
                ['name' => 'Meals & Allowances', 'description' => 'Staff meals, transport stipends, and daily allowances.'],
                ['name' => 'Staff Training', 'description' => 'Workshops, training sessions, certifications, and learning materials.'],
                ['name' => 'Recruitment Costs', 'description' => 'Advertising, interviews, and onboarding of new employees.'],
                ['name' => 'Uniforms', 'description' => 'Purchase and replacement of staff uniforms or dress code items.'],
            ],

            'guest_services' => [
                ['name' => 'Toiletries & Amenities', 'description' => 'Guest supplies like soap, shampoo, tissues, and slippers.'],
                ['name' => 'Breakfast / Welcome Snacks', 'description' => 'Complimentary snacks, meals, or breakfast offered to guests.'],
                ['name' => 'Complimentary Water / Tea', 'description' => 'Bottled water, tea, or coffee offered as a guest courtesy.'],
                ['name' => 'Linen & Towels', 'description' => 'Sheets, pillowcases, towels and their replacements.'],
                ['name' => 'Guest Transport (Shuttles, etc.)', 'description' => 'Transport for guests including shuttle services or hired cars.'],
            ],

            'marketing' => [
                ['name' => 'OTA Commissions (Airbnb, Booking.com)', 'description' => 'Fees charged by online booking platforms.'],
                ['name' => 'Marketing & Advertising', 'description' => 'Facebook ads, Google ads, posters, and flyers.'],
                ['name' => 'Photography / Videography', 'description' => 'Professional photos or videos for promotional use.'],
                ['name' => 'Guest Gifts or Promotions', 'description' => 'Welcome gifts, coupons, or promotional giveaways.'],
            ],

            'property' => [
                ['name' => 'Rent or Mortgage', 'description' => 'Payments for property rent or mortgage installments.'],
                ['name' => 'Property Taxes', 'description' => 'Taxes related to property ownership or usage.'],
                ['name' => 'Insurance (Property, Fire, Liability)', 'description' => 'Insurance premiums covering damage or liability.'],
                ['name' => 'Lease Payments', 'description' => 'Installments for leasing equipment or space.'],
            ],

            'technology' => [
                ['name' => 'PMS / Channel Manager', 'description' => 'Software used for managing bookings, rooms, and inventory.'],
                ['name' => 'Wi-Fi or Internet Services', 'description' => 'Monthly charges for internet access for the business or guests.'],
                ['name' => 'Domain & Hosting', 'description' => 'Website domain name and hosting service fees.'],
                ['name' => 'POS System or Subscriptions', 'description' => 'Software subscriptions for payments, POS, or business tools.'],
            ],

            'financial_legal' => [
                ['name' => 'Bank Fees', 'description' => 'Charges for bank transfers, account maintenance, or ATM withdrawals.'],
                ['name' => 'Loan Repayments', 'description' => 'Monthly repayments for business or personal loans.'],
                ['name' => 'Accounting / Auditing', 'description' => 'Professional financial services like bookkeeping and audits.'],
                ['name' => 'Legal Services', 'description' => 'Lawyer fees or legal consultations.'],
                ['name' => 'Licenses & Permits', 'description' => 'Local business licenses, tourism permits, or certifications.'],
            ],

            'inventory' => [
                ['name' => 'Food & Beverages', 'description' => 'Stock for guest meals or bar service.'],
                ['name' => 'Cleaning Supplies', 'description' => 'Detergents, disinfectants, mops, and other cleaning tools.'],
                ['name' => 'Guest Room Stock', 'description' => 'Room items like water bottles, cups, and notepads.'],
                ['name' => 'Bar & Restaurant Inventory', 'description' => 'Ingredients and items for onsite restaurant/bar.'],
            ],

            'capital' => [
                ['name' => 'New Furniture / Fixtures', 'description' => 'Beds, chairs, tables, lighting or decorations.'],
                ['name' => 'Renovations', 'description' => 'Upgrades, remodeling, or repainting of rooms and buildings.'],
                ['name' => 'Equipment Purchase', 'description' => 'Buying machines, electronics, or kitchen gear.'],
            ],

            'miscellaneous' => [
                ['name' => 'Emergency Repairs', 'description' => 'Unexpected fixes needed urgently.'],
                ['name' => 'Unexpected Expenses', 'description' => 'Unplanned costs not fitting other categories.'],
                ['name' => 'Miscellaneous', 'description' => 'Other general costs that don’t belong elsewhere.'],
            ],
        ];

        foreach ($expenseCategories as $type => $categories) {
            foreach ($categories as $category) {
                ExpenseCategory::create([
                    'company_id' => $companyId,
                    'type' => $type,
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]);
            }
        }
    }

}
