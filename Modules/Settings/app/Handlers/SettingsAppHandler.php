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
 * @param int $companyId
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
                'manage_allotments',
                'manage_rate_plans',
                'manage_availability',
                'override_rate',
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
                'process_payments',
                'view_rates',
                'manage_guests',
            ],

            // Housekeeping
            'housekeeping' => [
                'view_housekeeping_board',
                'update_room_status',
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

            // POS — split into granular groups for least-privilege
            'pos_core' => [ // creating & managing orders at the table
                'access_pos',
                'create_pos_order',
                'update_pos_order',
                'cancel_pos_order',
                'send_to_kitchen',
                'send_to_bar',
                'apply_line_discount',     // small/limited discounts
                'apply_bill_discount',     // bill-level discount
                'hold_resume_order',
                'split_bill',
                'merge_bills',
                'transfer_order',          // move to another waiter
                'change_service_type',     // dine-in/takeaway/delivery
                'reprint_last_receipt',    // for guest copy
                'print_kitchen_ticket',
                'print_bar_ticket',
                'void_pos_item',           // **requires supervisor in many orgs**
            ],

            'pos_cash' => [ // sensitive cash-drawer/settlement operations
                'process_pos_payment',
                'open_pos_session',
                'close_pos_session',
                'cash_drop',
                'count_cash_drawer',
                'refund_pos_order',
                'open_cash_drawer',
                'override_price',                 // manager/supervisor only
                'approve_discount_over_threshold',// manager/supervisor only
                'view_pos_sessions',
                'view_pos_payments',
            ],

            'pos_tables' => [ // seating & table management
                'view_floor',
                'assign_table',
                'release_table',
                'move_table',
            ],

            'pos_kitchen' => [ // KDS permissions (kitchen/bar screens)
                'view_kds',
                'view_bar_kds',
                'mark_item_preparing',
                'mark_item_ready',
                'mark_item_delivered',
                'bump_kds_item',
                'recall_kds_item',
            ],

            'pos_catalog' => [ // menu/product management
                'manage_pos_products',
                'manage_pos_categories',
                'manage_pos_taxes',
                'manage_pos_pricelists',
                'manage_pos_modifiers',
            ],

            // Property & Rooms
            'properties' => [
                'view_properties',
                'manage_properties',
                'create_properties',
                'manage_policies',
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

            // Guest portal (optional)
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

            // Operations leader — everything except the truly sensitive billing/role mgmt
            'manager' => array_merge(
                $sectionPermissions['overview'],
                $sectionPermissions['reservations'],
                $sectionPermissions['front_office'],
                $sectionPermissions['housekeeping'],
                $sectionPermissions['maintenance'],
                $sectionPermissions['accounting'],
                $sectionPermissions['properties'],
                $sectionPermissions['rooms'],
                // POS (all sub-groups)
                $sectionPermissions['pos_core'],
                $sectionPermissions['pos_cash'],
                $sectionPermissions['pos_tables'],
                $sectionPermissions['pos_kitchen'],
                $sectionPermissions['pos_catalog'],
                // Settings minus sensitive
                array_diff($sectionPermissions['settings'], ['manage_billing', 'manage_roles']),
                // Users minus manage_roles (optional)
                array_diff($sectionPermissions['users'], ['manage_roles'])
            ),

            // Front desk/concierge
            'front-office' => array_merge(
                $sectionPermissions['front_office'],
                ['view_reservations','create_reservations','modify_reservations','cancel_reservations','manage_guest_profiles','preauthorize_payments','take_deposits','view_rates'],
                $sectionPermissions['rooms']
            ),

            // Reservations team
            'reservations' => array_merge(
                $sectionPermissions['reservations'],
                $sectionPermissions['rooms']
            ),

            // Housekeeping & Maintenance
            'housekeeping' => array_merge($sectionPermissions['housekeeping'], ['view_rooms']),
            'maintenance'  => array_merge($sectionPermissions['maintenance'],  ['view_rooms']),

            // Finance
            'accounting' => array_merge(
                $sectionPermissions['accounting'],
                ['view_reports','view_financial_reports','view_pos_reports']
            ),

            // POS ROLES (new)
            'waiter' => array_merge(
                $sectionPermissions['pos_core'],
                $sectionPermissions['pos_tables'],
                // Typically no payments/refunds/overrides — keep cash functions out
                []
            ),

            'cashier' => array_merge(
                // Payment & drawer operations; can view core to finalize at counter if needed
                ['access_pos','reprint_last_receipt'],
                $sectionPermissions['pos_cash']
            ),

            'kitchen' => [
                // KDS only — no access to cash or order editing
                'view_kds','mark_item_preparing','mark_item_ready','bump_kds_item','recall_kds_item'
            ],

            'bar' => [
                'view_bar_kds','mark_item_preparing','mark_item_ready','bump_kds_item','recall_kds_item'
            ],

            'host' => [
                'view_floor','assign_table','release_table','move_table'
            ],

            // Shift supervisor — approvals for sensitive actions without full manager scope
            'shift-supervisor' => array_merge(
                ['access_pos','reprint_last_receipt'],
                [
                    'approve_discount_over_threshold',
                    'override_price',
                    'refund_pos_order',
                    'open_cash_drawer'
                ],
                // optional: allow session close if manager absent
                ['close_pos_session','count_cash_drawer','cash_drop']
            ),
        ];

        // ---------- CREATE ROLES & ASSIGN ----------
        foreach ($rolesPermissions as $role => $permissions) {
            $roleInstance = Role::firstOrCreate([
                'name'       => $role,
                'company_id' => $companyId,
            ]);
            $roleInstance->syncPermissions($permissions);
        }
    }


}
