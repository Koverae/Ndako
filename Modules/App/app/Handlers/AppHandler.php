<?php
namespace Modules\App\Handlers;

use App\Models\Module\InstalledModule;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
// use Modules\Dashboards\Entities\InstalledDashboard;

abstract  class AppHandler
{
    /**
     * Each module should define its own specific installation logic.
     */
    abstract protected function handleInstallation(int $company);

    /**
     * Each module should define its own specific uninstallation logic.
     */
    abstract protected function handleUninstallation();
    
    /**
     * Retrieve the module slug - to be implemented by each module.
     */
    abstract protected function getModuleSlug();

    /**
     * Common installation method which includes logging and transaction handling.
     */
    public function install($company, $user){
        try {
            Log::info("Starting installation of module: " . static::class);

            $this->validatePrerequisites();

            // DB::beginTransaction();

            // Perform the installation steps
            $this->handleInstallation($company);

            // Load configurations if installation is successful
            $this->loadConfiguration();

            // Record the successful installation
            $this->recordInstallation($company, $user);

            // DB::commit();

            Log::info("Successfully installed module: " . static::class);
        } catch (Exception $e) {
            // DB::rollBack();
            Log::error("Error installing module " . static::class . ": " . $e->getMessage());
            throw $e;
        }
    }

    public function uninstall()
    {
        try {
            Log::info("Starting uninstallation of module: " . $this->getModuleSlug());

            DB::beginTransaction();

            $this->handleUninstallation();
            $this->unrecordInstallation();

            DB::commit();

            Log::info("Successfully uninstalled module: " . $this->getModuleSlug());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error uninstalling module " . $this->getModuleSlug() . ": " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Load module configurations. This can be overridden by specific handlers if needed.
     */
    protected function loadConfiguration()
    {
        // Load and merge configuration logic
    }

    /**
     * Validate prerequisites for module installation.
     */
    protected function validatePrerequisites()
    {
        // Validate the necessary environment or conditions
    }

    /**
     * Record the successful installation of a module in the database.
     */
    protected function recordInstallation($company, $user)
    {
        InstalledModule::create([
            'module_slug' => $this->getModuleSlug(),
            'company_id' => $company,
            'installed_by' => $user,
            'is_approved' => true
        ]);
    }

    private function unrecordInstallation()
    {
        InstalledModule::where('module_slug', $this->getModuleSlug())
                       ->delete();
    }

}