<?php

namespace Modules\RevenueManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
            'operational' => [
                ['name' => 'Utilities (Electricity, Water, Internet)', 'description' => 'Monthly bills for electricity, water supply, internet and phone services.'],
                ['name' => 'Cleaning & Laundry', 'description' => 'Costs for room cleaning, linen washing, and external laundry services.'],
                ['name' => 'Maintenance & Repairs', 'description' => 'General repairs, equipment maintenance, plumbing, and electrical work.'],
                ['name' => 'Waste Management', 'description' => 'Garbage collection and disposal services.'],
                ['name' => 'Security Services', 'description' => 'Security staff, surveillance systems, and third-party security providers.'],
                ['name' => 'Transportation / Fuel', 'description' => 'Fuel and transport expenses for logistics, errands or guest transfers.'],
                ['name' => 'Office Supplies', 'description' => 'Stationery, paper, pens, printing materials, and related items.'],
            ],

            'staffing' => [
                ['name' => 'Staff Salaries / Payroll', 'description' => 'Total salary payments to all employees.'],
                ['name' => 'Overtime & Bonuses', 'description' => 'Extra payments for staff overtime or performance bonuses.'],
                ['name' => 'Meals & Allowances', 'description' => 'Staff meals, transport stipends, and daily allowances.'],
                ['name' => 'Staff Training', 'description' => 'Workshops, training sessions, certifications, and learning materials.'],
                ['name' => 'Recruitment Costs', 'description' => 'Advertising, interviews, and onboarding of new employees.'],
                ['name' => 'Uniforms', 'description' => 'Purchase and replacement of staff uniforms or dress code items.'],
            ],

            'guest_services' => [
                ['name' => 'Toiletries & Amenities', 'description' => 'Guest supplies like soap, shampoo, tissues, and slippers.'],
                ['name' => 'Breakfast / Welcome Snacks', 'description' => 'Complimentary snacks, meals, or breakfast offered to guests.'],
                ['name' => 'Complimentary Water / Tea', 'description' => 'Bottled water, tea, or coffee offered as a guest courtesy.'],
                ['name' => 'Linen & Towels', 'description' => 'Sheets, pillowcases, towels and their replacements.'],
                ['name' => 'Guest Transport (Shuttles, etc.)', 'description' => 'Transport for guests including shuttle services or hired cars.'],
            ],

            'marketing' => [
                ['name' => 'OTA Commissions (Airbnb, Booking.com)', 'description' => 'Fees charged by online booking platforms.'],
                ['name' => 'Marketing & Advertising', 'description' => 'Facebook ads, Google ads, posters, and flyers.'],
                ['name' => 'Photography / Videography', 'description' => 'Professional photos or videos for promotional use.'],
                ['name' => 'Guest Gifts or Promotions', 'description' => 'Welcome gifts, coupons, or promotional giveaways.'],
            ],

            'property' => [
                ['name' => 'Rent or Mortgage', 'description' => 'Payments for property rent or mortgage installments.'],
                ['name' => 'Property Taxes', 'description' => 'Taxes related to property ownership or usage.'],
                ['name' => 'Insurance (Property, Fire, Liability)', 'description' => 'Insurance premiums covering damage or liability.'],
                ['name' => 'Lease Payments', 'description' => 'Installments for leasing equipment or space.'],
            ],

            'technology' => [
                ['name' => 'PMS / Channel Manager', 'description' => 'Software used for managing bookings, rooms, and inventory.'],
                ['name' => 'Wi-Fi or Internet Services', 'description' => 'Monthly charges for internet access for the business or guests.'],
                ['name' => 'Domain & Hosting', 'description' => 'Website domain name and hosting service fees.'],
                ['name' => 'POS System or Subscriptions', 'description' => 'Software subscriptions for payments, POS, or business tools.'],
            ],

            'financial_legal' => [
                ['name' => 'Bank Fees', 'description' => 'Charges for bank transfers, account maintenance, or ATM withdrawals.'],
                ['name' => 'Loan Repayments', 'description' => 'Monthly repayments for business or personal loans.'],
                ['name' => 'Accounting / Auditing', 'description' => 'Professional financial services like bookkeeping and audits.'],
                ['name' => 'Legal Services', 'description' => 'Lawyer fees or legal consultations.'],
                ['name' => 'Licenses & Permits', 'description' => 'Local business licenses, tourism permits, or certifications.'],
            ],

            'inventory' => [
                ['name' => 'Food & Beverages', 'description' => 'Stock for guest meals or bar service.'],
                ['name' => 'Cleaning Supplies', 'description' => 'Detergents, disinfectants, mops, and other cleaning tools.'],
                ['name' => 'Guest Room Stock', 'description' => 'Room items like water bottles, cups, and notepads.'],
                ['name' => 'Bar & Restaurant Inventory', 'description' => 'Ingredients and items for onsite restaurant/bar.'],
            ],

            'capital' => [
                ['name' => 'New Furniture / Fixtures', 'description' => 'Beds, chairs, tables, lighting or decorations.'],
                ['name' => 'Renovations', 'description' => 'Upgrades, remodeling, or repainting of rooms and buildings.'],
                ['name' => 'Equipment Purchase', 'description' => 'Buying machines, electronics, or kitchen gear.'],
            ],

            'miscellaneous' => [
                ['name' => 'Emergency Repairs', 'description' => 'Unexpected fixes needed urgently.'],
                ['name' => 'Unexpected Expenses', 'description' => 'Unplanned costs not fitting other categories.'],
                ['name' => 'Miscellaneous', 'description' => 'Other general costs that don’t belong elsewhere.'],
            ],
        ];

        foreach ($expenseCategories as $type => $categories) {
            foreach ($categories as $category) {
                DB::table('expense_categories')->insert([
                    'company_id' => current_company()->id,
                    'type' => $type,
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
