<?php
namespace Modules\FrontDesk\Handlers;

use App\Models\Company\Company;
use Modules\App\Handlers\AppHandler;
use Modules\ChannelManager\Models\Channel\Channel;
use Modules\FrontDesk\Models\Desk\FrontDesk;
use Modules\Properties\Models\Property\Property;

class FrontDeskAppHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'front-desk';
    }

    protected function handleInstallation($company)
    {
        // Example: Create data and initial configuration
        $this->configure($company);

    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    public function configure($companyId){
        $properties = Property::isCompany($companyId)->get();

        foreach ($properties as $property){
            $this->configureFrontDesk($property->id, $companyId);
        }
    }

    public function configureFrontDesk($property, $company){
        
        $company = Company::find($company);
        $property = Property::find($property);
        $desk = FrontDesk::create([
            'company_id' => $company->id,
            'property_id' => $property->id,
            'name' => $property->name.' Front Desk',
            'description' => 'Front desk for your company',
            // 'channel_id' => Channel::first()->id,
        ]);
        $desk->save();
        $desk->setting()->create([
            'company_id' => $company->id,
            'setting_id' => $company->setting->id,
            'front_desk_id' => $desk->id,
        ]);
    }

}