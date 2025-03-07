<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\App\Database\Seeders\AppDatabaseSeeder;
use Modules\Settings\Database\Seeders\RolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RolePermissionSeeder::class,
            // AppDatabaseSeeder::class,
            PlanTableSeeder::class,
            SuperUserSeeder::class
        ]);
        // User::factory()->count(10)->create();
    }
}
