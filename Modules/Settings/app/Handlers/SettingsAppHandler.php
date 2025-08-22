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
        $company = Company::find($companyId);
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

        // ---------- PERMISSION GROUPS ----------
        $sectionPermissions = [

            // Dashboards & Reports
            'overview' => [
                'view_dashboard',
                'view_reports',
                'view_reservation_reports',
                'view_property_reports',
                'view_financial_reports',
                'view_pos_reports',
                'view_audit_reports',
            ],

            // Reservations (pre-stay)
            'reservations' => [
                'view_reservations',
                'create_reservations',
                'modify_reservations',
                'cancel_reservations',
                'manage_allotments',       // groups/corporates/blocks
                'manage_rate_plans',       // create/edit rate plans & policies
                'manage_availability',     // open/close rooms, stop-sell
                'override_rate',           // per-booking price override
                'preauthorize_payments',
                'take_deposits',
                'manage_guest_profiles',
            ],

            // Front Office (on-property)
            'front_office' => [
                'check_in_guests',
                'check_out_guests',
                'assign_rooms',
                'room_move',
                'issue_keys',
                'handle_walkins',
                'mark_noshow',
                'extend_stay',
                'post_charges',
                'void_charges',
                'process_payments',        // settle folio payments
                'view_rates',              // see rates/availability (no edit)
                'manage_guests',
            ],

            // Housekeeping
            'housekeeping' => [
                'view_housekeeping_board',
                'update_room_status',      // clean/dirty/OOS/OOSvc
                'create_hk_task',
                'complete_hk_task',
                'view_lost_and_found',
            ],

            // Maintenance
            'maintenance' => [
                'create_work_orders',
                'update_work_orders',
                'close_work_orders',
                'set_room_out_of_service',
                'view_maintenance_tasks',
            ],

            // Accounting & Night Audit
            'accounting' => [
                'manage_invoices',
                'manage_expenses',
                'process_refunds',
                'reconcile_payments',
                'export_financials',
                'run_night_audit',
                'close_day',
                'view_financial_reports',
            ],

            // POS
            'pos' => [
                'access_pos',
                'manage_pos_orders',
                'process_pos_payment',
                'open_pos_session',
                'close_pos_session',
                'cash_drop',
                'view_pos_sessions',
                'view_pos_payments',
                'manage_pos_products',
            ],

            // Property & Rooms
            'properties' => [
                'view_properties',
                'manage_properties',
                'create_properties',
                'manage_policies',         // cancellation, no-show, fees
                'manage_fees_taxes',
            ],
            'rooms' => [
                'view_rooms',
                'create_rooms',
                'manage_rooms',
            ],

            // Settings & Users
            'settings' => [
                'access_settings',
                'modify_settings',
                'manage_billing',
                'access_companies',
                'manage_integrations',
                'manage_channel_manager',
                'create_pos',
                'manage_pos_settings',
            ],
            'users' => [
                'view_users',
                'manage_staff',
                'manage_roles',
                'invite_users',
                'modify_own_profile',
            ],

            // App (Ndako/Koverae)
            'app' => [
                'manage_kover_subscription',
                'install_pwa',
            ],

            // (Optional) Guest portal – not assigned here
            'guest' => [
                'view_own_reservations',
                'update_profile',
                'request_housekeeping',
            ],
        ];

        // ---------- CREATE ALL PERMISSIONS ----------
        $allPermissionNames = collect($sectionPermissions)->flatten()->unique()->values();
        foreach ($sectionPermissions as $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'company_id' => $companyId,
                ]);
            }
        }

        // ---------- ROLE → PERMISSIONS MAP ----------
        $rolesPermissions = [
            // Full access
            'owner' => $allPermissionNames->all(),

            // Ops + settings + reports (but keep a few owner-only like manage_billing/manage_roles if you want)
            'manager' => array_merge(
                $sectionPermissions['overview'],
                $sectionPermissions['reservations'],
                $sectionPermissions['front_office'],
                $sectionPermissions['housekeeping'],
                $sectionPermissions['maintenance'],
                $sectionPermissions['accounting'],
                $sectionPermissions['pos'],
                $sectionPermissions['properties'],
                $sectionPermissions['rooms'],
                // Settings minus the sensitive bits (adjust to taste)
                array_diff($sectionPermissions['settings'], ['manage_billing', 'manage_roles']),
                // Users (without manage_roles if you want it owner-only)
                array_diff($sectionPermissions['users'], ['manage_roles'])
            ),

            // Desk/Concierge (includes basic reservation ops but NOT rates/availability management)
            'front-office' => array_merge(
                $sectionPermissions['front_office'],
                // limited reservations
                ['view_reservations','create_reservations','modify_reservations','cancel_reservations','manage_guest_profiles','preauthorize_payments','take_deposits','view_rates'],
                $sectionPermissions['rooms'], // view/manage room assignment context
            ),

            // Dedicated bookings team (can manage rates/availability if you prefer — here we allow it)
            'reservations' => array_merge(
                $sectionPermissions['reservations'],
                $sectionPermissions['rooms']   // usually need room visibility
            ),

            // HK & Maintenance
            'housekeeping' => array_merge(
                $sectionPermissions['housekeeping'],
                ['view_rooms'] // visibility only
            ),
            'maintenance' => array_merge(
                $sectionPermissions['maintenance'],
                ['view_rooms'] // visibility only
            ),

            // Finance
            'accounting' => array_merge(
                $sectionPermissions['accounting'],
                // Reports visibility
                ['view_reports','view_financial_reports','view_pos_reports']
            ),

            // POS Staff (no product management by default)
            'cashier' => [
                'access_pos','manage_pos_orders','process_pos_payment',
                'open_pos_session','close_pos_session','cash_drop',
                'view_pos_sessions','view_pos_payments',
            ],
        ];

        // ---------- CREATE ROLES & ASSIGN ----------
        foreach ($rolesPermissions as $role => $permissions) {
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'company_id' => $companyId,
            ]);
            $roleInstance->syncPermissions($permissions);
        }
    }

}
