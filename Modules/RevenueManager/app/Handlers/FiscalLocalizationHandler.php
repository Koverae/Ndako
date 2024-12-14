<?php
namespace Modules\RevenueManager\Handlers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\App\Handlers\AppHandler;
use Modules\RevenueManager\Models\Accounting\FiscalPackage;
use Modules\RevenueManager\Models\Tax\Tax;

class FiscalLocalizationHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'revenue-manager';
    }

    protected function handleInstallation($company)
    {
        // Example: Create fiscal-localization data and initial configuration
        $this->createGenericFiscalLocalization($company);
        $this->createKenyaFiscalLocalization($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    // Chart Of Account
    private function createChartOfAccount(int $companyId, string $country_code = null){
        $chart = [
            [
                'name' => 'Chart Of Account',
            ]
        ];
    }

    private function createGenericFiscalLocalization(int $companyId){
        
        $fiscalPackages = [
            [
                'name' => 'Generic Chart Template',
                'code' => 'generic',
                'country_code' => null
            ],
            [
                'name' => 'ᴋᴇ Kenya',
                'code' => 'KE',
                'country_code' => 'KE'
            ],
            [
                'name' => 'ᴜɢ Uganda',
                'code' => 'UG',
                'country_code' => 'UG'
            ],
        ];
        foreach($fiscalPackages as $localization){
            FiscalPackage::create(array_merge(['company_id' => $companyId], $localization));
        }

        // Taxes
        $taxes = [

            // **Sales Taxes**
            [
                'name' => 'VAT (18%)',
                'description' => 'Sales VAT 18%',
                'wording_on_invoice' => 'VAT 18%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 18.00,
                'amount' => 18.00,
            ],
            [
                'name' => 'VAT (16%)',
                'description' => 'Sales VAT 16%',
                'wording_on_invoice' => 'VAT 16%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 16.00,
                'amount' => 16.00,
            ],
            [
                'name' => 'VAT (8%)',
                'description' => 'Sales VAT 8%',
                'wording_on_invoice' => 'VAT 8%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 8.00,
                'amount' => 8.00,
            ],
            [
                'name' => 'Zero Rated VAT',
                'description' => 'Sales VAT Zero Rated',
                'wording_on_invoice' => '0% VAT',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
            [
                'name' => 'VAT Exempt',
                'description' => 'Sales VAT Exempt',
                'wording_on_invoice' => 'Exempt',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
        
            // **Withholding Taxes (WHT)**
            [
                'name' => 'WHT (5%)',
                'description' => '5% Withholding Tax',
                'wording_on_invoice' => 'WHT 5%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => -5.00,
                'amount' => -5.00,
            ],
            [
                'name' => 'WHT (10%)',
                'description' => '10% Withholding Tax',
                'wording_on_invoice' => 'WHT 10%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => -10.00,
                'amount' => -10.00,
            ],
        
            // **Purchase Taxes**
            [
                'name' => 'VAT (16%) - Purchases',
                'description' => 'Purchases VAT 16%',
                'wording_on_invoice' => 'VAT 16%',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 16.00,
                'amount' => 16.00,
            ],
            [
                'name' => 'VAT (8%) - Purchases',
                'description' => 'Purchases VAT 8%',
                'wording_on_invoice' => 'VAT 8%',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 8.00,
                'amount' => 8.00,
            ],
            [
                'name' => 'Zero Rated VAT - Purchases',
                'description' => 'Purchases VAT Zero Rated',
                'wording_on_invoice' => '0% VAT',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
            [
                'name' => 'VAT Exempt - Purchases',
                'description' => 'Purchases VAT Exempt',
                'wording_on_invoice' => 'Exempt',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
        ];
        foreach($taxes as $tax){
            Tax::create(array_merge(['company_id' => $companyId], $tax));
        }

    }

    // Kenya Fiscal Localization
    private function createKenyaFiscalLocalization(int $companyId, string $country_code = null){

        // Taxes
        $taxes = [
            // **Sales Taxes**
            [
                'name' => 'VAT (16%)',
                'description' => 'Sales VAT 16%',
                'wording_on_invoice' => 'VAT 16%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 16.00,
                'amount' => 16.00,
            ],
            [
                'name' => 'VAT (8%)',
                'description' => 'Sales VAT 8%',
                'wording_on_invoice' => 'VAT 8%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 8.00,
                'amount' => 8.00,
            ],
            [
                'name' => 'Zero Rated VAT',
                'description' => 'Sales VAT Zero Rated',
                'wording_on_invoice' => '0% VAT',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
            [
                'name' => 'VAT Exempt',
                'description' => 'Sales VAT Exempt',
                'wording_on_invoice' => 'Exempt',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
        
            // **Withholding Taxes (WHT)**
            [
                'name' => 'WHT (5%)',
                'description' => '5% Withholding Tax',
                'wording_on_invoice' => 'WHT 5%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => -5.00,
                'amount' => -5.00,
            ],
            [
                'name' => 'WHT (10%)',
                'description' => '10% Withholding Tax',
                'wording_on_invoice' => 'WHT 10%',
                'computation_type' => 'percentage',
                'type' => 'sales',
                'rate' => -10.00,
                'amount' => -10.00,
            ],
        
            // **Purchase Taxes**
            [
                'name' => 'VAT (16%) - Purchases',
                'description' => 'Purchases VAT 16%',
                'wording_on_invoice' => 'VAT 16%',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 16.00,
                'amount' => 16.00,
            ],
            [
                'name' => 'VAT (8%) - Purchases',
                'description' => 'Purchases VAT 8%',
                'wording_on_invoice' => 'VAT 8%',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 8.00,
                'amount' => 8.00,
            ],
            [
                'name' => 'Zero Rated VAT - Purchases',
                'description' => 'Purchases VAT Zero Rated',
                'wording_on_invoice' => '0% VAT',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
            [
                'name' => 'VAT Exempt - Purchases',
                'description' => 'Purchases VAT Exempt',
                'wording_on_invoice' => 'Exempt',
                'computation_type' => 'percentage',
                'type' => 'purchases',
                'rate' => 0.00,
                'amount' => 0.00,
            ],
        ];
               
        foreach($taxes as $tax){
            Tax::create(array_merge(['company_id' => $companyId, 'country_code' => $country_code], $tax));
        }

    }


}