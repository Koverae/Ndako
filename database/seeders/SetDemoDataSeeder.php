<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use App\Models\Team\Team;
use App\Models\Company\Company;
use App\Models\User;
use Koverae\KoveraeBilling\Models\Plan;
use Modules\App\Handlers\AppManagerHandler;
use Modules\Pos\Models\Product\Product;
use Modules\Pos\Models\Product\ProductCategory;
use Illuminate\Support\Str;

class SetDemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoUsers = [
            // ['name' => 'Arden BOUET - Manager', 'email' => 'ardenbouet@mambaresorts.com', 'phone' => '+254745908945', 'role' => 'manager'],
            ['name' => 'Brian Mwangi - Receptionist', 'email' => 'brianmwagi@mambaresorts.com', 'phone' => '+254755938945', 'role' => 'front-desk'],
            ['name' => 'Sam Baraka - Maintenance Staff', 'email' => 'sambaraka@mambaresorts.com', 'phone' => '+254732878945', 'role' => 'maintenance-staff'],
        ];

        $user = User::create([
            'name' => 'Arden BOUET',
            'email' => 'ardenbouet@mambaresorts.com',
            'phone' => '+254745908945',
            'password' => Hash::make('ndako'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $user->save();

        $team = Team::create([
            'user_id' => $user->id
        ]);
        $team->save();

        $plan = Plan::getByTag('spark-yearly');
        $subscription = $team->newSubscription(
            'main', // identifier tag of the subscription. If your application offers a single subscription, you might call this 'main' or 'primary'
             $plan, // Plan or PlanCombination instance your subscriber is subscribing to
             'Main subscription', // Human-readable name for your subscription
             'Customer main subscription', // Description
             null, // Start date for the subscription, defaults to now()
             'free' // Payment method service defined in config
        );

        $company = Company::create([
            'team_id' => $team->id,
            'owner_id' => $user->id,
            'name' => "Mamba Resorts",
            'website' => "https://mamba-resorts.com",
            'city' => "Nairobi",
            'country_id' => 12,
            'industry' => "hotel",
            'size' => 32,
            'primary_interest' => 'manage_my_business',
            'default_currency_id' => 12,
            'is_onboarded' => true
        ]);
        $company->save();

        $user->update([
            'company_id' => $company->id,
            'current_company_id' => $company->id,
        ]);

        // Install Modules
        $appManager = new AppManagerHandler;
        $appManager->installModules($company->id, $user->id);

        $user->update([
            'company_id' => $company->id,
            'current_company_id' => $company->id,
            // 'language_id' => $this->language
        ]);
        $user->save();
        $user->assignRole('manager');

        // $user->givePermissionTo('manage_kover_subscription');

        foreach($demoUsers as $demoUser){
            $demoU = User::create([
                'company_id' => 1,
                'current_company_id' => 1,
                'name' => $demoUser['name'],
                'email' => $demoUser['email'],
                'phone' => $demoUser['phone'],
                'password' => Hash::make("ndako"),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);
            $demoU->save();

            $demoU->assignRole($demoUser['role']);
        }
    }

}
