<?php

namespace Modules\App\Database\Seeders;

use App\Models\Module\Module;
use App\Models\Module\ModuleCategory;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            // 1- Accounting
            [
                'name' => 'Accounting',
                'slug' => 'accounting',
                'parent_id' => null,
                'type' => 'app_category',
            ],
            // 2- Sales
            [
                'name' => 'Reservation',
                'slug' => 'reservation',
                'parent_id' => null,
                'type' => 'app_category',
            ],
            // 3- Inventory
            [
                'name' => 'Inventory',
                'slug' => 'inventory',
                'parent_id' => null,
                'type' => 'app_category',
            ],
            // 5- Human Resources
            [
                'name' => 'Human Resources',
                'slug' => 'human_resources',
                'parent_id' => null,
                'type' => 'app_category',
            ],
            // 6- Operations
            [
                'name' => 'Operations',
                'slug' => 'operations',
                'parent_id' => null,
                'type' => 'app_category',
            ],
            // 7- Productivity
            [
                'name' => 'Productivity',
                'slug' => 'productivity',
                'parent_id' => null,
                'type' => 'app_category',
            ],
        ];

        foreach ($categories as $category) {
            ModuleCategory::create($category);

        }

        $modules = [
            // App Manager
            [
                'name' => 'Applications Manager',
                'slug' => 'apps',
                'short_name'    =>  'Apps Manager',
                'description'  =>  'Efficiently manage and oversee all your applications in one place.',
                'app_group' => 'app_settings',
                'version'  => 'v1.0',
                'author'    => 'Koverae Ltd',
                'icon'  => 'apps',
                'is_default'    => 1,
                'link'  => 'apps.index',
                'path'  => "app::layouts.navbar-menu",
                'navbar_id' => 1,
                'enabled'   => 1
            ],

            // Settings
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'short_name'    =>  'Settings',
                'description'  =>  'Configure your preferences to optimize your experience.',
                'app_group' => 'app_settings',
                'version'  => 'v1.0',
                'author'    => 'Koverae Ltd',
                'icon'  => 'settings',
                'is_default'    => 1,
                'link'  => 'settings.general',
                'path'  => "settings::layouts.navbar-menu",
                'navbar_id' => 2,
                'enabled'   => 1
            ],

            // Properties
            [
                'name' => 'Properties',
                'slug' => 'properties',
                'short_name'    =>  'Properties',
                'description'  =>  'Manage your properties effortlessly, from listings to tenant details, all in one place.',
                'app_group' => 'app_settings',
                'version'  => 'v1.0',
                'author'    => 'Koverae Ltd',
                'icon'  => 'reservation',
                'is_default'    => 0,
                'link'  => 'properties.index',
                'path'  => "properties::layouts.navbar-menu",
                'navbar_id' => 3,
                'enabled'   => 1
            ],

            // Channel Manager
            [
                'name' => 'Channel Manager',
                'slug' => 'channel-manager',
                'short_name'    =>  'Channel Manager',
                'description'  =>  'Simplifies property management by seamlessly synchronizing reservations, rates, and availability across multiple booking platforms in real-time.',
                'app_group' => 'app_settings',
                'version'  => 'v1.0',
                'author'    => 'Koverae Ltd',
                'icon'  => 'channel-manager',
                'is_default'    => 0,
                'link'  => 'channels.index',
                'path'  => "channelmanager::layouts.navbar-menu",
                'navbar_id' => 4,
                'enabled'   => 1
            ],

        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
