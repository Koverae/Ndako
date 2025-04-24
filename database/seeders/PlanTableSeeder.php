<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Koverae\KoveraeBilling\Models\Plan;
use Koverae\KoveraeBilling\Models\PlanFeature;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ndako Starter
        $starterFeatures = [
            ['tag' => 'properties', 'name' => 'Properties', 'value' => 1, 'sort_order' => 0],
            ['tag' => 'units', 'name' => 'Rooms/Units', 'value' => 20, 'sort_order' => 1],
            ['tag' => 'direct-booking', 'name' => 'Direct Booking Management', 'value' => 'basic', 'sort_order' => 2],
            ['tag' => 'guest-management', 'name' => 'Guest Management', 'value' => 'basic', 'sort_order' => 3],
            ['tag' => 'invoicing', 'name' => 'Invoicing', 'value' => 'basic', 'sort_order' => 4],
            ['tag' => 'basic-reporting', 'name' => 'Basic Reporting', 'value' => 'basic', 'sort_order' => 5],
            ['tag' => 'mobile-access', 'name' => 'Mobile Access', 'value' => true, 'sort_order' => 6],
            ['tag' => 'support', 'name' => 'Support', 'value' => 'basic', 'sort_order' => 7],

            ['tag' => 'revenue-management', 'name' => 'Revenue Management', 'value' => false, 'sort_order' => 8],
            ['tag' => 'integrations', 'name' => 'Integrations', 'value' => false, 'sort_order' => 9],
            ['tag' => 'custom-branding', 'name' => 'Custom Branding', 'value' => false, 'sort_order' => 10],
            ['tag' => 'custom-roles', 'name' => 'Custom User Roles & Permissions', 'value' => false, 'sort_order' => 11],
            ['tag' => 'bulk-import-export', 'name' => 'Bulk Import/Export', 'value' => false, 'sort_order' => 12],
            ['tag' => 'ota-connector', 'name' => 'OTA Connector', 'value' => false, 'sort_order' => 13],
            ['tag' => 'website-integration', 'name' => 'Website Integration', 'value' => false, 'sort_order' => 7],
            ['tag' => 'guest-portal', 'name' => 'Guest Portal', 'value' => false, 'sort_order' => 16],
        ];
        $starterPlan = Plan::create([
            'tag' => 'starter',
            'name' => 'Ndako Starter',
            'description' => 'For 1-15 rooms | Ideal for small hotels, boutique stays, and guesthouses',
            'price' => 0.00,
            'signup_fee' => 0.00,
            'invoice_period' => 2,
            'invoice_interval' => 'year',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'grace_period' => 1,
            'grace_interval' => 'day',
            'tier' => 1,
            'currency' => 'KES',
            'is_free' => true,
        ]);
        // $starterPlan->features()->saveMany($starterFeatures);
        foreach ($starterFeatures as $feature) {
            PlanFeature::create(array_merge(['plan_id' => $starterPlan->id], $feature));
        }

        // Ndako Spark
        $sparkFeatures = [
            ['tag' => 'properties', 'name' => 'Properties', 'value' => 1, 'sort_order' => 0],
            ['tag' => 'units', 'name' => 'Rooms/Units', 'value' => 100, 'sort_order' => 1],
            ['tag' => 'direct-booking', 'name' => 'Direct Booking Management', 'value' => 'advanced', 'sort_order' => 2],
            ['tag' => 'guest-management', 'name' => 'Guest Management', 'value' => 'advanced', 'sort_order' => 3],
            ['tag' => 'invoicing', 'name' => 'Invoicing', 'value' => 'customizable', 'sort_order' => 4],
            ['tag' => 'reporting', 'name' => 'Basic Reporting', 'value' => 'advanced', 'sort_order' => 5],
            ['tag' => 'mobile-access', 'name' => 'Mobile Access', 'value' => true, 'sort_order' => 6],
            ['tag' => 'support', 'name' => 'Support', 'value' => 'priority', 'sort_order' => 7],

            ['tag' => 'revenue-management', 'name' => 'Revenue Management', 'value' => 'basic', 'sort_order' => 8],
            ['tag' => 'integrations', 'name' => 'Integrations', 'value' => 'some', 'sort_order' => 9],
            ['tag' => 'custom-branding', 'name' => 'Custom Branding', 'value' => 'limited', 'sort_order' => 10],
            ['tag' => 'custom-roles', 'name' => 'Custom User Roles & Permissions', 'value' => false, 'sort_order' => 11],
            ['tag' => 'bulk-import-export', 'name' => 'Bulk Import/Export', 'value' => false, 'sort_order' => 12],
            ['tag' => 'ota-connector', 'name' => 'OTA Connector', 'value' => false, 'sort_order' => 13],
            ['tag' => 'website-integration', 'name' => 'Website Integration', 'value' => 'bridge', 'sort_order' => 7],
            ['tag' => 'guest-portal', 'name' => 'Guest Portal', 'value' => false, 'sort_order' => 15],
        ];
        $sparkMonthly = Plan::create([
            'tag' => 'spark-monthly',
            'name' => 'Ndako Spark',
            'description' => 'For 11-105 rooms | Mid-sized hotels seamlessly streamlining daily business operations.',
            'price' => 480.00, //For first sub, 312/room (-35%)
            'discounted_price' => 312.00, //For first sub, 312/room (-35%)
            'signup_fee' => 0.00,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 7,
            'trial_interval' => 'day',
            'grace_period' => 1,
            'grace_interval' => 'day',
            'tier' => 1,
            'currency' => 'KES',
        ]);
        // $sparkMonthly->features()->saveMany($sparkFeatures);
        foreach ($sparkFeatures as $feature) {
            PlanFeature::create(array_merge(['plan_id' => $sparkMonthly->id], $feature));
        }

        $sparkYearly = Plan::create([
            'tag' => 'spark-yearly',
            'name' => 'Ndako Spark',
            'description' => 'For 11-65 rooms | Mid-sized hotels seamlessly streamlining daily business operations.',
            'price' => 4800.00, //KSh 400/room/month, For first sub, KSh 260/room (-35%)
            'discounted_price' => 3120.00, //KSh 260/room/month, For first sub, KSh 260/room (-35%)
            'signup_fee' => 0.00,
            'invoice_period' => 1,
            'invoice_interval' => 'year',
            'trial_period' => 7,
            'trial_interval' => 'day',
            'grace_period' => 1,
            'grace_interval' => 'day',
            'tier' => 1,
            'currency' => 'KES',
        ]);
        // $sparkYearly->features()->saveMany($sparkFeatures);
        foreach ($sparkFeatures as $feature) {
            PlanFeature::create(array_merge(['plan_id' => $sparkYearly->id], $feature));
        }

        // Ndako Enterprise
        $enterpriseFeatures = [
            ['tag' => 'properties', 'name' => 'Properties', 'value' => 30, 'sort_order' => 0],
            ['tag' => 'units', 'name' => 'Rooms/Units', 'value' => 150, 'sort_order' => 1],
            ['tag' => 'direct-booking', 'name' => 'Direct Booking Management', 'value' => 'advanced', 'sort_order' => 2],
            ['tag' => 'guest-management', 'name' => 'Guest Management', 'value' => 'advanced', 'sort_order' => 3],
            ['tag' => 'invoicing', 'name' => 'Invoicing', 'value' => 'customizable', 'sort_order' => 4],
            ['tag' => 'reporting', 'name' => 'Basic Reporting', 'value' => 'advanced', 'sort_order' => 5],
            ['tag' => 'mobile-access', 'name' => 'Mobile Access', 'value' => true, 'sort_order' => 6],
            ['tag' => 'support', 'name' => 'Support', 'value' => 'full-priority', 'sort_order' => 7],

            ['tag' => 'revenue-management', 'name' => 'Revenue Management', 'value' => 'advanced', 'sort_order' => 8],
            ['tag' => 'integrations', 'name' => 'Integrations', 'value' => 'full-access', 'sort_order' => 9],
            ['tag' => 'custom-branding', 'name' => 'Custom Branding', 'value' => 'full-access', 'sort_order' => 10],
            ['tag' => 'custom-roles', 'name' => 'Custom User Roles & Permissions', 'value' => true, 'sort_order' => 11],
            ['tag' => 'bulk-import-export', 'name' => 'Bulk Import/Export', 'value' => true, 'sort_order' => 12],
            ['tag' => 'ota-connector', 'name' => 'OTA Connector', 'value' => true, 'sort_order' => 13],
            ['tag' => 'website-integration', 'name' => 'Website Integration', 'value' => true, 'sort_order' => 7],
            ['tag' => 'guest-portal', 'name' => 'Guest Portal', 'value' => true, 'sort_order' => 15],
        ];
        $enterpriseMonthly = Plan::create([
            'tag' => 'enterprise-monthly',
            'name' => 'Ndako Enterprise',
            'description' => 'For 66-150 rooms | Larger properties and hotel chains requiring advanced features.',
            'price' => 650.00, //For first sub, KSh 422/room (-35%)
            'discounted_price' => 422.00, //For first sub, KSh 422/room (-35%)
            'signup_fee' => 0.00,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 7,
            'trial_interval' => 'day',
            'grace_period' => 1,
            'grace_interval' => 'day',
            'tier' => 1,
            'currency' => 'KES',
            'is_active' => false
        ]);
        // $enterpriseMonthly->features()->saveMany($enterpriseFeatures);
        foreach ($enterpriseFeatures as $feature) {
            PlanFeature::create(array_merge(['plan_id' => $enterpriseMonthly->id], $feature));
        }

        $enterpriseYearly = Plan::create([
            'tag' => 'enterprise-yearly',
            'name' => 'Ndako Enterprise',
            'description' => 'For 66-150 rooms | Larger properties and hotel chains requiring advanced features.',
            'price' => 6480.00, //KSh 540/room/month, For first sub, KSh 350/room (-35%)
            'discounted_price' => 4200.00, //KSh 350/room/month, For first sub, KSh 350/room (-35%)
            'signup_fee' => 0.00,
            'invoice_period' => 1,
            'invoice_interval' => 'year',
            'trial_period' => 7,
            'trial_interval' => 'day',
            'grace_period' => 1,
            'grace_interval' => 'day',
            'tier' => 1,
            'currency' => 'KES',
            'is_active' => false
        ]);
        // $enterpriseYearly->features()->saveMany($enterpriseFeatures);
        foreach ($enterpriseFeatures as $feature) {
            PlanFeature::create(array_merge(['plan_id' => $enterpriseYearly->id], $feature));
        }

    }
}
