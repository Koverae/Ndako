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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $this->installRolesAndPermissions($company);
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
        $defaultCurrency = Currency::find($company->default_currency_id);

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

    /**
     * Install default company roles and permissions.
     *
     * @param Company $company
     */
    private function installRolesAndPermissions(int $companyId): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // Hotel Manager
            'access_settings',
            'modify_settings',
            'view_users',
            'manage_roles',
            'invite_users',
            'manage_reservations',
            'view_reports',
            'view_reservation_reports',
            'view_property_reports',
            'manage_rooms',
            'manage_staff',
            'manage_properties',
            'create_units',
            'manage_billing',

            // Front Desk / Receptionist
            'create_reservations',
            'modify_reservations',
            'view_rooms',
            'check_in_guests',
            'check_out_guests',
            'manage_guest_profiles',
            'assign_rooms',

            // Maintenance Staff
            'view_maintenance_tasks',
            'update_task_status',

            // Accountant
            'view_financial_reports',
            'manage_invoices',
            'manage_expenses',
            'process_refunds',

            // Guest
            'view_own_reservations',
            'update_profile',
            'request_housekeeping',

            // App
            'manage_kover_subscription',
            'install_pwa',
            'modify_own_profile',

        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'company_id' => $companyId]);
        }

        // Create Roles and Assign Permissions
        $rolesPermissions = [
            'owner' => [
                'access_settings',
                'modify_settings',
                'view_users',
                'manage_roles',
                'invite_users',
                'manage_reservations',
                'manage_properties',
                'view_reports',
                'view_reservation_reports',
                'view_financial_reports',
                'view_property_reports',
                'manage_rooms',
                'manage_expenses',
                'create_units',
                'manage_staff',
                'manage_billing',
                'view_maintenance_tasks',
            ],
            'manager' => [
                'access_settings',
                'modify_settings',
                'view_users',
                'manage_roles',
                'invite_users',
                'manage_reservations',
                'manage_properties',
                'view_reports',
                'view_reservation_reports',
                'view_financial_reports',
                'view_property_reports',
                'manage_rooms',
                'create_units',
                'manage_staff',
                'manage_billing',
                'view_maintenance_tasks',
            ],
            'front-desk' => [
                'manage_reservations',
                'manage_rooms',
                'view_rooms',
                'create_reservations',
                'modify_reservations',
                'check_in_guests',
                'check_out_guests',
                'manage_guest_profiles',
                'assign_rooms',
                // 'manage_expenses',
            ],
            'maintenance-staff' => [
                'view_maintenance_tasks',
                'update_task_status',
                'view_rooms',
            ],
            'accountant' => [
                'view_financial_reports',
                'manage_invoices',
                'manage_expenses',
                'process_refunds',
            ],
            'guest' => [
                'view_own_reservations',
                'update_profile',
                'request_housekeeping',
            ],
        ];

        foreach ($rolesPermissions as $role => $permissions) {
            $roleInstance = Role::create(['name' => $role, 'company_id' => $companyId]);
            $roleInstance->givePermissionTo($permissions);
        }
    }
}
