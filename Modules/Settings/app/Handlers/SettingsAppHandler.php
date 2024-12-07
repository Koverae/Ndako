<?php
namespace Modules\Settings\Handlers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\App\Handlers\AppHandler;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Models\System\Setting;
use Modules\Settings\Models\SystemParameter;
use Ramsey\Uuid\Uuid;

class SettingsAppHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'settings';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        $this->installCompanySettings($company);
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
    private function installCompanySettings(int $companyId): void
    {
        $company = Company::find($companyId)->first();
        $defaultCurrency = Currency::isCompany($companyId)->where('code', $company->default_currency)->first();

        $database_uuid = Uuid::uuid4();
        $database_secret = generate_unique_database_secret();
        
        SystemParameter::create([
            'company_id' => $companyId,
            'database_create_date' => now(),
            'database_expiration_date' => now()->addDays(14),
            'account_online_distribution_mode' => 'trial',
            'database_secret' => $database_secret,
            'database_uuid' => $database_uuid,
        ]);

        Setting::create([
            'company_id' => $company->id,
            'default_currency_id' => $defaultCurrency->id,
            'default_currency_position' => $defaultCurrency->symbol_position,
        ]);
    }
}