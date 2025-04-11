<?php

use App\Models\Company\Company;
use App\Models\Module\InstalledModule;
use App\Models\Module\Module;
use App\Models\Team\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Koverae\KoveraeBilling\Models\Plan;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Settings\Models\System\Setting;
use Modules\Settings\Models\SystemParameter;
use Modules\Settings\Models\WorkItem;

if (!function_exists('domains')) {
    function domains() {
        $companies = Company::all();

        $subdomains = $companies->pluck('domain_name'); // Pluck the 'subdomain' field from each company

        return $subdomains;
    }
}

if(!function_exists('current_company')){
    function current_company() {

        if (session()->has('current_company')) {
            // The 'current_company' session variable is available
            // You can access it like this:
            $currentCompany = session('current_company');

            // Perform actions with $currentCompany
            return $currentCompany;
        } else {
            // The 'current_company' session variable is not available
            // Handle the case when the session is not active or the variable is not set
        }

    }
}


if (!function_exists('settings')) {
    function settings() {
        $settings = '';
        if(current_company()){
            $settings = cache()->remember('settings', 24*60, function () {
                return Setting::where('company_id', current_company()->id)
                ->first();
            });
        }

        return $settings;
    }
}

//************ ****************//
// Module
//************ ****************//

if (!function_exists('modules')) {
    function modules() {
        $modules = Module::all();

        return $modules;
    }
}

if (!function_exists('module')) {
    function module($slug) {

        $module = Module::findBySlug($slug)->first();

        $company = Company::find(current_company()->id);

        if($module){
            return $module->isInstalledBy($company);
        }else{
            return false;
        }
    }
}


if(!function_exists('installed_apps')){
    function installed_apps($company){
        $installed_apps = InstalledModule::where('company_id', $company->id)->get();
        return $installed_apps;
    }
}

if(!function_exists(function: 'current_module')){
    function current_module() {

        $module = modules()->where('navbar_id', session('current_menu'))->first();
        if($module){
            return $module;
        }
        return modules()->first();
    }
}

//************ ****************//
// Navbar Menu
//************ ****************//

if (!function_exists('updated_menu')) {
    function updated_menu($module) {

        $storedArray = Cache::get('current_menu');

        // Check if the array exists in the cache
        if ($storedArray != null) {
            // Modify the array as needed
            $storedArray['name'] = $module->name;
            $storedArray['path'] = $module->path;
            $storedArray['id'] = $module->navbar_id;
            $storedArray['slug'] = $module->slug;

            // Store the modified array back in the cache with the same key
            $navbar = Cache::put('current_menu', $storedArray, 60); // Adjust the expiration time if needed
            return $navbar;
        }

        // Storing the array in the cache with a key and expiration time (in minutes)
        $cookie = Cache::put('current_menu', [
            'name' => $module->name,
            'path' => $module->path,
            'id' => $module->navbar_id,
            'slug' => $module->slug
        ],
        120);

        return $cookie;


        // No need to return a value here, as Cache::put doesn't return anything
    }
}
// Current Menu
if (!function_exists('update_menu')) {
    function update_menu($navbar){
        // Store company information in the session or a cookie
        session()->forget('current_menu');

        $menu = session(['current_menu' => $navbar]);

        return $menu;
    }
}


if (!function_exists('current_menu')) {
    function current_menu() {

        // Retrieve the current array from the cache

        $menu = session('current_company');
        return $menu;

    }
}

if (!function_exists('generate_unique_database_secret')) {
    function generate_unique_database_secret() {
        $prefix = 'KOV';
        $allowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            // Create 3 segments with 3 characters each (letters and numbers)
            $segments = [];
            for ($i = 0; $i < 3; $i++) {
                $segments[] = substr(str_shuffle($allowedChars), 0, 3);
            }

            // Join the segments with dashes
            $kovString = $prefix . '-' . implode('-', $segments);

            // Check if the string already exists in the database
        } while (SystemParameter::where('database_secret', $kovString)->exists());

        return $kovString;
    }
}

//************ ****************//
// Currency ********************
//************ ****************//

if (!function_exists('format_currency')) {
    function format_currency($value, $format = true) {
        if (!$format) {
            return $value;
        }

        $settings = settings();
        $currency = settings()->currency;
        $position = $currency->symbol_position;
        $symbol = $currency->symbol;
        $decimal_separator = $currency->decimal_separator;
        $thousand_separator = $currency->thousand_separator;

        if ($position == 'prefix') {
            $formatted_value = $symbol . number_format((float) $value, 2, $decimal_separator, $thousand_separator);
        } else {
            $formatted_value = number_format((float) $value, 2, $decimal_separator, $thousand_separator) .' '. $symbol;
        }

        return $formatted_value;
    }
}

//************ ****************//
// Input ***********************
//************ ****************//

if (!function_exists('modelToSelectOptions')) {
    /**
     * Convert model collection to key-value pairs for select options.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $collection
     * @param  string $valueField The attribute to use for option values
     * @param  string $textField The attribute to use for option text
     * @return array
     */
    function modelToSelectOptions($collection, $valueField = 'id', $textField = 'name')
    {
        return $collection->pluck($textField, $valueField)->toArray();
    }
}

if (!function_exists('toSelectOptions')) {
    /**
     * Convert a model collection or an array to key-value pairs for select options.
     *
     * @param  mixed $data The data to convert, can be an Eloquent collection or an array
     * @param  string $valueField The attribute or key to use for option values
     * @param  string $textField The attribute or key to use for option text
     * @return array
     */
    function toSelectOptions($data, $valueField = 'id', $textField = 'name', $selectedValue = null)
    {
        if (is_array($data)) {
            // If it's an array, transform it assuming it's an array of arrays or objects
            return array_column($data, $textField, $valueField);
        } elseif ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            // If it's an Eloquent Collection, use pluck
            return $data->pluck($textField, $valueField)->toArray();
        }

        return [];
    }
}

if (!function_exists('toRadioOptions')) {
    /**
     * Convert a model collection or an array to radio input options.
     *
     * @param  mixed $data The data to convert, can be an Eloquent collection or an array
     * @param  string $valueField The attribute or key to use for option values
     * @param  string $textField The attribute or key to use for option text
     * @param  mixed $checkedValue The value of the radio button that should be checked by default
     * @return array
     */
    function toRadioOptions($data, $valueField = 'id', $textField = 'name', $checkedValue = null)
    {
        $options = [];
        if (is_array($data)) {
            // Handle the array data
            foreach ($data as $item) {
                $options[] = [
                    'value' => $item[$valueField],
                    'label' => $item[$textField],
                    'checked' => ($item[$valueField] == $checkedValue)
                ];
            }
        } elseif ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            // Handle the Eloquent Collection
            foreach ($data as $item) {
                $options[] = [
                    'value' => $item->$valueField,
                    'label' => $item->$textField,
                    'checked' => ($item->$valueField == $checkedValue)
                ];
            }
        }

        return $options;
    }
}

if (!function_exists('route')) {
    /**
     * Generate a route with a subdomain parameter based on the current company.
     *
     * @param string $name The name of the route.
     * @param array $parameters The route parameters.
     * @param bool $absolute Whether the URL should be absolute.
     * @return string
     */
    function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        if (function_exists('current_company') && $domain = current_company()->domain_name) {
            $parameters['subdomain'] = $domain;
        }

        return route($name, $parameters, $absolute);
    }
}

//************ ****************//
// Reference *******************
//************ ****************//

if (!function_exists('make_reference_id')) {
    function make_reference_id($prefix, $number) {
        $padded_text = $prefix . '-' . str_pad($number, 5, 0, STR_PAD_LEFT);

        return $padded_text;
    }
}

if (!function_exists('make_reference_with_id')) {
    function make_reference_with_id($prefix, $number, $year) {
        $padded_text = $prefix . '/'.$year.'/'. str_pad($number, 5, 0, STR_PAD_LEFT);

        return $padded_text;
    }
}

if (!function_exists('make_reference_with_month_id')) {
    function make_reference_with_month_id($prefix, $number, $year, $month) {
        $padded_text = $prefix . '/'.$year. '/'.$month.'/'. str_pad($number, 5, 0, STR_PAD_LEFT);

        return $padded_text;
    }
}

//************ ****************//
// Dates ***********************
//************ ****************//

if (!function_exists('dateDaysDifference')) {
    function dateDaysDifference($date1, $date2) {
        $d1 = Carbon::parse($date1);
        $d2 = Carbon::parse($date2);
        $days = $d1->diffInDays($d2);
        return $days;
    }
}


if (!function_exists('listMonthsInRange')) {
    function listMonthsInRange($startDate, $endDate)
    {
        // dd($startDate, $endDate);
        // Create Carbon instances for the start and end date
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        // Array to hold the result
        $months = [];

        // Loop through the months
        while ($start->lte($end)) {
            // Add the month and code to the array
            $months[] = [
                'month' => $start->format('F Y'),       // 'April 2025'
                'code'  => $start->format('Y_m')        // '2025_04'
            ];

            // Move to the next month
            $start->addMonth();
        }

        return $months;
    }
}

if (!function_exists('getYearAndMonthFromCode')) {
    function getYearAndMonthFromCode($code)
    {
        // Split the code into year and month
        list($year, $month) = explode('_', $code);

        // Parse the month and year to create a Carbon instance
        $date = Carbon::createFromDate($year, $month, 1); // Set the day as 1st for the given month and year

        // Extract year and month
        return [
            'year'  => $date->year,
            'month' => $date->month,
        ];
    }
}

if (!function_exists('getRentDueDate')) {
    function getRentDueDate(string $monthYear): string
    {
        // Convert the month string into a Carbon date
        $date = Carbon::createFromFormat('F Y', $monthYear);

        // Get the 1st day of the next month
        $dueDate = $date->addMonth()->startOfMonth();

        // Format and return the due date as yyyy/mm/dd
        return $dueDate->format('Y/m/d');
    }
}

if (!function_exists('formatDateToCode')) {
    function formatDateToCode($date)
    {
        return Carbon::parse($date)->format('Y_m'); // Example: '2025_04'
    }
}

//************ ****************//
// Work Items ******************
//************ ****************//
if(!function_exists('createRoomPreparationTask')){
    function createRoomPreparationTask($reservation)
    {
        WorkItem::create([
            'title' => "Prepare Room #{$reservation->room_number}",
            'description' => "Ensure the room is ready for guest {$reservation->guest_name}.",
            'type' => 'task',
            'status' => 'pending',
            'priority' => 'medium',
            'related_id' => $reservation->id,
            'assigned_to' => 1, // Default to housekeeping team
            'created_by' => auth()->id() ?? null,
        ]);
    }
}


//************ ****************//
// Unit Pricing ******************
//************ ****************//
if(!function_exists('lease_term')){
    function lease_term($id)
    {
        $leaseTerm = LeaseTerm::find($id);

        return $leaseTerm;
    }
}

// if(!function_exists('lease_term')){
//     function lease_term($lease_duration)
//     {
//         $lease_terms = [
//             '1' => '1 month',
//             '2' => '2 months',
//             '3' => '3 months',
//             '4' => '4 months',
//             '5' => '5 months',
//             '6' => '6 months',
//             '7' => '7 months',
//             '8' => '8 months',
//             '9' => '9 months',
//             '10' => '10 months',
//             '11' => '11 months',
//             '12' => '12 months',
//         ];

//         return $lease_terms[$lease_duration];
//     }
// }

if(!function_exists('hasFeature')){
    function hasFeature(Team $team, string $feature): bool
    {
        $plan = $team->subscription('main');
        if (!$plan) {
            return false; // No plan assigned
        }

        $features = $plan->features()->pluck('value', 'tag')->toArray();

        // Ensure the feature exists and is explicitly true
        return array_key_exists($feature, $features) && $features[$feature] == true;
    }
}

if(!function_exists('getRemainingTrialDays')){
    function getRemainingTrialDays(){
        $remainingTime = '';

        $subscription = current_company()->team->subscription('main');
        $daysLeft = $subscription->getTrialPeriodRemainingUsageIn('day');
        $hoursLeft = $subscription->getTrialPeriodRemainingUsageIn('hour');

        if($daysLeft >= 1){
            $remainingTime = $daysLeft.' days';
        }else{
            $remainingTime = $hoursLeft.' hours';
        }

        return $remainingTime;

    }
}

if(!function_exists('getRemainingSubDays')){
    function getRemainingSubDays(){
        $remainingTime = '';

        $subscription = current_company()->team->subscription('main');
        $daysLeft = $subscription->getSubscriptionPeriodRemainingUsageIn('day');
        $hoursLeft = $subscription->getSubscriptionPeriodRemainingUsageIn('hour');

        if($daysLeft >= 1){
            $remainingTime = $daysLeft.' days';
        }else{
            $remainingTime = $hoursLeft.' hours';
        }

        return $remainingTime;

    }
}

if (!function_exists('getFinalPrice')) {
    function getFinalPrice(float $price, bool $isFirstSubscription = true, float $discountPercentage = 35): float
    {
        if ($isFirstSubscription) {
            $discount = $price * ($discountPercentage / 100);
            return round($price - $discount, 2);
        }

        return $price;
    }
}

if (!function_exists('calculatePrice')) {
    function calculatePrice(int $roomCount, float $roomPrice, $discountedPrice = null): float
    {
        // Use the discounted price if available; otherwise, use the standard price
        $finalPrice = $discountedPrice ?? $roomPrice;
        $roomCount = max(1, $roomCount ?? 1); // Ensure at least 1 room

        return round($roomCount * $finalPrice, 2);
    }
}

if (!function_exists('calculateEndDate')) {
    function calculateEndDate(string $interval = 'month', $period = 1): Carbon
    {
        $period = (int) $period; // Ensure it's an integer

        return match ($interval) {
            'month' => now()->addMonths($period),
            'year'  => now()->addYears($period),
            default => now()->addDays(30), // Fallback to 30 days if undefined
        };
    }
}

// if (!function_exists('calculateSubscriptionEndDate')) {
//     function calculateSubscriptionEndDate($subscription): Carbon
//     {
//         return match ($subscription->invoice_interval) {
//             'month' => Carbon::parse($subscription->starts_at) now()->addMonth(),
//             'year'  => now()->addYear(),
//             default   => now()->addDays(30), // Fallback to 30 days if undefined
//         };
//     }
// }

if(!function_exists('getPlan')){
    function getPlan($tag = null){
        $plan = Plan::getByTag($tag);
        $planName = $plan->name.' '. ucfirst($plan->invoice_interval).'ly';

        return $planName;
    }
}

if(!function_exists('getProperty')){
    function getProperty($id){
        $property = Property::find($id);

        return $property;
    }
}

if(!function_exists('getPropertyUnit')){
    function getPropertyUnit($id){
        $property = PropertyUnit::find($id);

        return $property;
    }
}

if (!function_exists('calculateDownPayment')) {
    /**
     * Calculate the down payment based on price and percentage.
     *
     */
    function calculateDownPayment($price, $percentage)
    {
        $downPayment = ($price * $percentage) / 100;
        return $downPayment;
    }
}


function inverseSlug(string $slug): string
{
    return Str::of($slug)
        ->replace(['-', '_'], ' ') // Replace hyphens and underscores with spaces
        ->title(); // Capitalize each word
}

if(!function_exists('current_property')){
    function current_property(){
        $property = cache()->remember('current_property', 24*60, function () {
            return Auth::user()->current_property_id ? Property::find(Auth::user()->current_property_id) : Property::isCompany(current_company()->id)->get()->first();
        });

        return $property;
    }
}
