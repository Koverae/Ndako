<?php

use App\Models\Company\Company;
use App\Models\Module\InstalledModule;
use App\Models\Module\Module;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Properties\Models\Property\LeaseTerm;
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
        $settings = cache()->remember('settings', 24*60, function () {
            return Setting::where('company_id', current_company()->id)
            ->first();
        });

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
