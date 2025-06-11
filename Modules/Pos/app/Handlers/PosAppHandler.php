<?php
namespace Modules\Pos\Handlers;

use Modules\App\Handlers\AppHandler;
use Modules\ChannelManager\Models\Channel\Channel;

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

}
