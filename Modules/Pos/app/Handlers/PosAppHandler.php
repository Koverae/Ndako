<?php
namespace Modules\Pos\Handlers;

use App\Models\Company\Company;
use Modules\App\Handlers\AppHandler;
use Modules\ChannelManager\Models\Channel\Channel;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSetting;
use Modules\Pos\Models\Product\Product;
use Modules\Pos\Models\Product\ProductCategory;
use Modules\RevenueManager\Models\Accounting\Journal;

class PosAppHandler extends AppHandler{

    protected function getModuleSlug()
    {
        return 'pos';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        // $this->configure($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    protected function configure($companyId){

        $company = Company::find($companyId);
        $pos = Pos::create([
            'company_id' => $company->id,
            'name' => $company->name.' Restaurant',
            'has_multiple_employee' => $company->multiple_employee,
            'is_restaurant' => true,
        ]);

        PosSetting::create([
            'company_id' => $pos->company_id,
            'pos_id' => $pos->id,
        ]);

        // Set Payment Methods
        $paymentMethods = Journal::whereNotIn('type', ['miscellaneous', 'sale', 'purchase', 'paystack'])->isCompany(current_company()->id)->get();
        $pos->setting->payment_methods = $paymentMethods->pluck('id')->toArray();
        $pos->setting->save();

        // Set Product Categories

        if(env('APP_DISTRIBUTION') === "demo"){
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

}
