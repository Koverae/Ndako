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

        $user = User::factory()->create([
            'name' => 'Arden BOUET',
            'email' => 'ardenbouet@mambaresorts.com',
            'phone' => '+254745908945',
            'password' => Hash::make('ndako'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
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

    public function demoConfig(){
        
        // Product Categories and Products
        $categories = [
            'Main Dishes' => [
                ['Ugali Beef', 180, ['Regular', 'Large']],
                ['Ugali Sukuma', 100, ['Plain', 'with Onions']],
                ['Ugali Matumbo', 160, ['Spicy', 'Mild']],
                ['Beef Stew', 200, ['Boneless', 'With Bones']],
                ['Chicken Stew', 220, ['Wet Fry', 'Dry Fry']],
                ['Nyama Choma (Beef)', 250, ['250g', '500g', '1kg']],
                ['Kuku Choma', 300, ['Half', 'Full']],
                ['Fish Fry (Tilapia)', 350, ['Whole', 'Fillet']],
                ['Fish Stew', 280, ['With Coconut', 'Plain']],
                ['Pilau Beef', 180, ['Small', 'Regular', 'Large']],
                ['Pilau Chicken', 190, ['Small', 'Regular', 'Large']],
                ['Chapati Beef', 180, ['1 chapati', '2 chapatis']],
                ['Chapati Beans', 130, ['1 chapati', '2 chapatis']],
                ['Githeri', 120, ['With Avocado', 'Plain']],
                ['Mukimo Beef', 180, ['With Kachumbari']],
                ['Mukimo Ndengu', 160, ['With Cabbage', 'Plain']],
            ],
            'Side Dishes' => [
                ['Ugali', 30, ['Small', 'Regular', 'Large']],
                ['Chapati', 25, ['Plain', 'Egg Chapati']],
                ['Plain Rice', 40, ['White', 'Coconut']],
                ['French Fries', 100, ['Regular', 'Large']],
                ['Mashed Potatoes', 90, ['With Butter', 'Plain']],
                ['Vegetable Fried Rice', 130, ['Spicy', 'Mild']],
                ['Stir-Fried Vegetables', 80, ['Mixed Veg', 'Sukuma']],
                ['Kachumbari', 20, ['Regular', 'Extra']],
                ['Managu', 60, ['With Cream', 'Plain']],
                ['Sukuma Wiki', 40, ['With Tomato', 'Plain']],
                ['Beans', 50, ['With Onions', 'Plain']],
                ['Ndengu', 60, ['With Carrots', 'Plain']],
                ['Cabbage', 40, ['Steamed', 'Fried']],
            ],
            // ... Add other categories like 'Breakfast Items', 'Fast Food / Quick Bites', etc. using same pattern
        ];

        foreach ($categories as $categoryName => $products) {
            $category = ProductCategory::create(['name' => $categoryName]);

            foreach ($products as [$name, $price, $variants]) {
                Product::create([
                    'product_category_id' => $category->id,
                    'name' => $name,
                    'price' => $price,
                    'variants' => $variants, // Will be auto-cast to JSON
                ]);
            }
        }
    }
}
