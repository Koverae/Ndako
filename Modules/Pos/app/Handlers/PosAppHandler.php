<?php
namespace Modules\Pos\Handlers;

use App\Models\Company\Company;
use Modules\App\Handlers\AppHandler;
use Modules\ChannelManager\Models\Channel\Channel;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSetting;

class PosAppHandler extends AppHandler{

    protected function getModuleSlug()
    {
        return 'pos';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        // $this->configure($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    protected function configure($companyId){

        $company = Company::find($companyId);
        $pos = Pos::create([
            'company_id' => $company->id,
            'name' => $company->name.' Restaurant',
            'has_multiple_employee' => $company->multiple_employee,
            'is_restaurant' => true,
        ]);

        PosSetting::create([
            'company_id' => $pos->company_id,
            'pos_id' => $pos->id,
        ]);
    }

}
