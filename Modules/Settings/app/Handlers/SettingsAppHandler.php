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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Grouped Permissions by Core Sections
        $sectionPermissions = [
            'overview' => [
                'view_reports',
                'view_reservation_reports',
                'view_property_reports',
                'view_financial_reports',
                'view_pos_reports',
            ],
            'reservations' => [
                'create_reservations',
                'modify_reservations',
                'manage_reservations',
                'view_reservation_payments',
                'check_in_guests',
                'check_out_guests',
                'assign_rooms',
                'manage_guest_profiles',
            ],
            'properties' => [
                'view_properties',
                'manage_properties',
                'create_properties',
                'create_rooms',
            ],
            'rooms' => [
                'view_rooms',
                'manage_rooms',
            ],
            'front_office' => [
                'create_reservations',
                'manage_guests',
                'check_in_guests',
                'check_out_guests',
                'assign_rooms',
            ],
            'operations' => [ // maintenance + housekeeping
                'view_maintenance_tasks',
                'update_task_status',
                'request_housekeeping',
            ],
            'accounting' => [
                'view_financial_reports',
                'manage_invoices',
                'manage_expenses',
                'process_refunds',
            ],
            'pos' => [
                'access_pos',
                'manage_pos_orders',
                'view_pos_sessions',
                'view_pos_payments',
                'process_pos_payment',
                'manage_pos_products',
            ],
            'settings' => [
                'access_settings',
                'modify_settings',
                'manage_billing',
                'access_companies',
                'create_pos',
                'manage_pos_settings',
            ],
            'users' => [
                'view_users',
                'manage_roles',
                'invite_users',
                'manage_staff',
                'modify_own_profile',
            ],
            'guest' => [
                'view_own_reservations',
                'update_profile',
                'request_housekeeping',
            ],
            'app' => [
                'manage_kover_subscription',
                'install_pwa',
            ],
        ];

        // Create All Permissions
        foreach ($sectionPermissions as $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'company_id' => $companyId,
                ]);
            }
        }

        // Role Definitions (based on section roles)
        $rolesPermissions = [
            'owner' => array_merge(
                $sectionPermissions['overview'],
                $sectionPermissions['reservations'],
                $sectionPermissions['properties'],
                $sectionPermissions['rooms'],
                $sectionPermissions['operations'],
                $sectionPermissions['accounting'],
                $sectionPermissions['settings'],
                $sectionPermissions['users']
            ),
            'manager' => array_merge(
                $sectionPermissions['overview'],
                $sectionPermissions['reservations'],
                $sectionPermissions['properties'],
                $sectionPermissions['rooms'],
                $sectionPermissions['operations'],
                $sectionPermissions['settings'],
                $sectionPermissions['users']
            ),
            'front-desk' => array_merge(
                $sectionPermissions['front_office'],
                $sectionPermissions['reservations'],
                $sectionPermissions['rooms']
            ),
            'maintenance-staff' => $sectionPermissions['operations'],
            'accountant' => $sectionPermissions['accounting'],
            'cashier' => $sectionPermissions['pos'],
            'guest' => $sectionPermissions['guest'],
        ];

        // Create Roles and Assign Permissions
        foreach ($rolesPermissions as $role => $permissions) {
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'company_id' => $companyId,
            ]);
            $roleInstance->syncPermissions($permissions);
        }
    }

}
