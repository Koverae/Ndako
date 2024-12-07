<?php
namespace Modules\RevenueManager\Handlers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\App\Handlers\AppHandler;
use Modules\RevenueManager\Models\Accounting\Journal;
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
        $this->createFollowUpLevels($company);

    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
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
        ];
        foreach($journals as $journal){
            Journal::create($journal);
        }
    }

    

}