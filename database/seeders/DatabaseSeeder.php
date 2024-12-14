<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\App\Database\Seeders\AppDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppDatabaseSeeder::class,
            SuperUserSeeder::class
        ]);
        User::factory()->count(50)->create();
    }
}
