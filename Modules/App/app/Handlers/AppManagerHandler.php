<?php
namespace Modules\App\Handlers;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\Wallet\KreditService;
use Modules\ChannelManager\Handlers\ChannelManagerAppHandler;
// use Modules\Dashboards\Handlers\DashboardsAppHandler;
use Modules\Inventory\Entities\UoM\UnitOfMeasureCategory;
use Modules\Inventory\Entities\UoM\UnitOfMeasure;
use Modules\Properties\Handlers\PropertiesAppHandler;
use Modules\FrontDesk\Handlers\FrontDeskAppHandler;
use Modules\RevenueManager\Handlers\FiscalLocalizationHandler;
use Modules\RevenueManager\Handlers\RevenueManagerAppHandler;
use Modules\Settings\Handlers\SettingsAppHandler;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Models\Language\Language;
use Illuminate\Support\Facades\Http;
use Modules\Pos\Handlers\PosAppHandler;
use Modules\Settings\Models\Localization\Country;

class AppManagerHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'apps';
    }

    protected function handleInstallation($company)
    {
        // Example: Create app-manager related data and initial configuration
        // $this->createWallet($company);
        // $this->installUnitsOfMeasure($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }


    /**
     * Install multiple modules at once, handling transactions and logging.
     *
     * @param array $modules An array of module handlers to install.
     * @param mixed $company The company context under which modules are being installed.
     * @param mixed $user The user performing the installation.
     */
    public function installModules($company, $user)
    {
        $modules = [
            new AppManagerHandler(),
            new SettingsAppHandler(),
            new PropertiesAppHandler(),
            new ChannelManagerAppHandler(),
            new RevenueManagerAppHandler(),
            new FiscalLocalizationHandler(),
            new PosAppHandler(),
            // new FrontDeskAppHandler(),
            // new DashboardsAppHandler(),
            // Add other module handlers as needed
        ];

        // Start transaction to ensure all modules are installed successfully or none at all.
        try {
            foreach ($modules as $module) {
                // Validate prerequisites before installation.
                $module->validatePrerequisites();

                // Execute module-specific installation logic.
                $module->handleInstallation($company);

                // Load any necessary configuration after installation.
                $module->loadConfiguration();

                // Record the installation in the database.
                $module->recordInstallation($company, $user);
            }
            // Commit the transaction if all installations are successful.
            Log::info("Successfully installed modules: " . implode(', ', array_map(function ($m) { return get_class($m); }, $modules)));
        } catch (Exception $e) {
            // Roll back the transaction if any installation fails.
            Log::error("Error installing modules: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create honorifics titles.
     *
     */
    public function configure(){
        $this->createCountries();
        $this->installCurrencies();
        $this->installLanguages();
    }


    /**
     * Create honorifics titles.
     *
     * @param int $companyId
     */
    private function createCountries(){

        $response = Http::timeout(30)->withoutVerifying()->retry(3, 100)
        ->get('https://restcountries.com/v3.1/all?fields=name,flags,cca2,currencies,startOfWeek,maps,idd,capital,region,subregion');

        if (!$response->successful()) {
            Log::error('Failed to fetch countries: ' . $response->body());
            return;
        }

        $countries = $response->json();

        foreach ($countries as $country) {
            Country::create([
                'common_name'    => $country['name']['common'] ?? 'Unknown',
                'official_name'  => $country['name']['official'] ?? 'Unknown',
                'country_code'   => $country['cca2'] ?? 'N/A',
                'currency_code'  => isset($country['currencies']) ? array_key_first($country['currencies']) : 'N/A',
                'flag'           => $country['flags']['svg'] ?? ($country['flags']['png'] ?? null),
                'start_of_week'  => $country['startOfWeek'] ?? null,
                'googleMaps'     => $country['maps']['googleMaps'] ?? null,
                'openStreetMaps' => $country['maps']['openStreetMaps'] ?? null,
                'country_calling_code'      => isset($country['idd']['root'], $country['idd']['suffixes'][0])
                                    ? $country['idd']['root'] . $country['idd']['suffixes'][0]
                                    : null,
                'capital'        => isset($country['capital'][0]) ? $country['capital'][0] : null,
                'region'         => $country['region'] ?? null,
                'subregion'      => $country['subregion'] ?? null,
                // 'languages'      => isset($country['languages']) ? implode(', ', array_values($country['languages'])) : null,
            ]);
        }
    }

    /**
     * Install currencies for the company.
     *
     *
     */
    private function installCurrencies(): void
    {
        $currencies = [
            // -------- Africa --------
            // North Africa
            ['currency_name' => 'Algerian Dinar',         'code' => 'DZD', 'symbol' => 'د.ج',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Egyptian Pound',         'code' => 'EGP', 'symbol' => '£',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Libyan Dinar',           'code' => 'LYD', 'symbol' => 'ل.د',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Moroccan Dirham',        'code' => 'MAD', 'symbol' => 'د.م.', 'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Sudanese Pound',         'code' => 'SDG', 'symbol' => '£',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Tunisian Dinar',         'code' => 'TND', 'symbol' => 'د.ت',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],

            // West Africa
            ['currency_name' => 'West African CFA Franc', 'code' => 'XOF', 'symbol' => 'CFA',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Nigerian Naira',         'code' => 'NGN', 'symbol' => '₦',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Ghanaian Cedi',          'code' => 'GHS', 'symbol' => 'GH₵',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Gambian Dalasi',         'code' => 'GMD', 'symbol' => 'D',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Guinean Franc',          'code' => 'GNF', 'symbol' => 'FG',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Liberian Dollar',        'code' => 'LRD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Cape Verdean Escudo',    'code' => 'CVE', 'symbol' => '$',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Sierra Leonean Leone',   'code' => 'SLE', 'symbol' => 'Le',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Central Africa
            ['currency_name' => 'Central African CFA Franc','code' => 'XAF','symbol' => 'FCFA','thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Angolan Kwanza',         'code' => 'AOA', 'symbol' => 'Kz',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Congolese Franc',        'code' => 'CDF', 'symbol' => 'FC',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'São Tomé and Príncipe Dobra','code' => 'STN','symbol' => 'Db','thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],

            // East Africa
            ['currency_name' => 'Kenyan Shilling',        'code' => 'KES', 'symbol' => 'KSh',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Ugandan Shilling',       'code' => 'UGX', 'symbol' => 'USh',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Tanzanian Shilling',     'code' => 'TZS', 'symbol' => 'TSh',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Rwandan Franc',          'code' => 'RWF', 'symbol' => 'RF',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Burundian Franc',        'code' => 'BIF', 'symbol' => 'FBu',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Ethiopian Birr',         'code' => 'ETB', 'symbol' => 'Br',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Djiboutian Franc',       'code' => 'DJF', 'symbol' => 'Fdj',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Eritrean Nakfa',         'code' => 'ERN', 'symbol' => 'Nfk',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Somali Shilling',        'code' => 'SOS', 'symbol' => 'Sh',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'South Sudanese Pound',   'code' => 'SSP', 'symbol' => '£',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Indian Ocean (Africa)
            ['currency_name' => 'Comorian Franc',         'code' => 'KMF', 'symbol' => 'CF',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Malagasy Ariary',        'code' => 'MGA', 'symbol' => 'Ar',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Mauritian Rupee',        'code' => 'MUR', 'symbol' => '₨',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Seychellois Rupee',      'code' => 'SCR', 'symbol' => '₨',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Southern Africa
            ['currency_name' => 'Botswana Pula',          'code' => 'BWP', 'symbol' => 'P',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'South African Rand',     'code' => 'ZAR', 'symbol' => 'R',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Namibian Dollar',        'code' => 'NAD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Zambian Kwacha',         'code' => 'ZMW', 'symbol' => 'K',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Zimbabwean Dollar',      'code' => 'ZWL', 'symbol' => 'Z$',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Mozambican Metical',     'code' => 'MZN', 'symbol' => 'MT',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Malawian Kwacha',        'code' => 'MWK', 'symbol' => 'MK',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Lesotho Loti',           'code' => 'LSL', 'symbol' => 'L',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Eswatini Lilangeni',     'code' => 'SZL', 'symbol' => 'E',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // -------- Europe --------
            // Eurozone
            ['currency_name' => 'Euro',                   'code' => 'EUR', 'symbol' => '€',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],

            // Nordics & UK/IE
            ['currency_name' => 'Danish Krone',           'code' => 'DKK', 'symbol' => 'kr.',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Norwegian Krone',        'code' => 'NOK', 'symbol' => 'kr',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Swedish Krona',          'code' => 'SEK', 'symbol' => 'kr',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'British Pound Sterling', 'code' => 'GBP', 'symbol' => '£',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Icelandic Króna',        'code' => 'ISK', 'symbol' => 'kr',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Swiss Franc',            'code' => 'CHF', 'symbol' => 'CHF',  'thousand_separator' => '\'', 'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Central & Eastern Europe (non-euro)
            ['currency_name' => 'Czech Koruna',           'code' => 'CZK', 'symbol' => 'Kč',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Polish Złoty',           'code' => 'PLN', 'symbol' => 'zł',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Hungarian Forint',       'code' => 'HUF', 'symbol' => 'Ft',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Romanian Leu',           'code' => 'RON', 'symbol' => 'lei',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Bulgarian Lev',          'code' => 'BGN', 'symbol' => 'лв.',  'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Serbian Dinar',          'code' => 'RSD', 'symbol' => 'дин',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Macedonian Denar',       'code' => 'MKD', 'symbol' => 'ден',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Albanian Lek',           'code' => 'ALL', 'symbol' => 'L',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Bosnia-Herzegovina Mark','code' => 'BAM', 'symbol' => 'KM',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Moldovan Leu',           'code' => 'MDL', 'symbol' => 'L',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Ukrainian Hryvnia',      'code' => 'UAH', 'symbol' => '₴',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Belarusian Ruble',       'code' => 'BYN', 'symbol' => 'Br',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Russian Ruble',          'code' => 'RUB', 'symbol' => '₽',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],

            // -------- Middle East & Caucasus --------
            ['currency_name' => 'United Arab Emirates Dirham', 'code' => 'AED', 'symbol' => 'د.إ', 'thousand_separator' => ',', 'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Saudi Riyal',            'code' => 'SAR', 'symbol' => '﷼',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Qatari Riyal',           'code' => 'QAR', 'symbol' => '﷼',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Kuwaiti Dinar',          'code' => 'KWD', 'symbol' => 'د.ك', 'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Omani Rial',             'code' => 'OMR', 'symbol' => '﷼',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Bahraini Dinar',         'code' => 'BHD', 'symbol' => '.د.ب', 'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Israeli New Shekel',     'code' => 'ILS', 'symbol' => '₪',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Jordanian Dinar',        'code' => 'JOD', 'symbol' => 'د.ا',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Iraqi Dinar',            'code' => 'IQD', 'symbol' => 'د.ع',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Iranian Rial',           'code' => 'IRR', 'symbol' => '﷼',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Lebanese Pound',         'code' => 'LBP', 'symbol' => 'ل.ل', 'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Syrian Pound',           'code' => 'SYP', 'symbol' => '£',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            // Caucasus
            ['currency_name' => 'Georgian Lari',          'code' => 'GEL', 'symbol' => '₾',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Armenian Dram',          'code' => 'AMD', 'symbol' => '֏',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Azerbaijani Manat',      'code' => 'AZN', 'symbol' => '₼',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],

            // -------- Asia (Far East, SE Asia, South & Central) --------
            // Far East
            ['currency_name' => 'Japanese Yen',           'code' => 'JPY', 'symbol' => '¥',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Chinese Yuan',           'code' => 'CNY', 'symbol' => '¥',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Hong Kong Dollar',       'code' => 'HKD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Macanese Pataca',        'code' => 'MOP', 'symbol' => 'MOP$', 'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'New Taiwan Dollar',      'code' => 'TWD', 'symbol' => 'NT$',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'South Korean Won',       'code' => 'KRW', 'symbol' => '₩',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Mongolian Tögrög',       'code' => 'MNT', 'symbol' => '₮',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Southeast Asia
            ['currency_name' => 'Singapore Dollar',       'code' => 'SGD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Malaysian Ringgit',      'code' => 'MYR', 'symbol' => 'RM',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Thai Baht',              'code' => 'THB', 'symbol' => '฿',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Indonesian Rupiah',      'code' => 'IDR', 'symbol' => 'Rp',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Philippine Peso',        'code' => 'PHP', 'symbol' => '₱',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Vietnamese Đồng',        'code' => 'VND', 'symbol' => '₫',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Cambodian Riel',         'code' => 'KHR', 'symbol' => '៛',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Lao Kip',                'code' => 'LAK', 'symbol' => '₭',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Myanmar Kyat',           'code' => 'MMK', 'symbol' => 'K',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Brunei Dollar',          'code' => 'BND', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // South Asia
            ['currency_name' => 'Indian Rupee',           'code' => 'INR', 'symbol' => '₹',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Pakistani Rupee',        'code' => 'PKR', 'symbol' => '₨',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Bangladeshi Taka',       'code' => 'BDT', 'symbol' => '৳',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Sri Lankan Rupee',       'code' => 'LKR', 'symbol' => 'Rs',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Nepalese Rupee',         'code' => 'NPR', 'symbol' => 'Rs',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Bhutanese Ngultrum',     'code' => 'BTN', 'symbol' => 'Nu.',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Maldivian Rufiyaa',      'code' => 'MVR', 'symbol' => 'Rf',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Afghan Afghani',         'code' => 'AFN', 'symbol' => '؋',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Central Asia
            ['currency_name' => 'Kazakhstani Tenge',      'code' => 'KZT', 'symbol' => '₸',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Uzbekistani Soʻm',       'code' => 'UZS', 'symbol' => 'soʻm', 'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Tajikistani Somoni',     'code' => 'TJS', 'symbol' => 'ЅМ',   'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Turkmenistan Manat',     'code' => 'TMT', 'symbol' => 'm',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Kyrgyzstani Som',        'code' => 'KGS', 'symbol' => 'сом',  'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],

            // -------- Americas --------
            // North America
            ['currency_name' => 'US Dollar',              'code' => 'USD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Canadian Dollar',        'code' => 'CAD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Mexican Peso',           'code' => 'MXN', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Central America
            ['currency_name' => 'Costa Rican Colón',      'code' => 'CRC', 'symbol' => '₡',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Guatemalan Quetzal',     'code' => 'GTQ', 'symbol' => 'Q',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Honduran Lempira',       'code' => 'HNL', 'symbol' => 'L',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Nicaraguan Córdoba',     'code' => 'NIO', 'symbol' => 'C$',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Panamanian Balboa',      'code' => 'PAB', 'symbol' => 'B/.',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Belize Dollar',          'code' => 'BZD', 'symbol' => 'BZ$',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // Caribbean & Atlantic
            ['currency_name' => 'Dominican Peso',         'code' => 'DOP', 'symbol' => 'RD$',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Cuban Peso',             'code' => 'CUP', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Haitian Gourde',         'code' => 'HTG', 'symbol' => 'G',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Jamaican Dollar',        'code' => 'JMD', 'symbol' => 'J$',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'East Caribbean Dollar',  'code' => 'XCD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Barbadian Dollar',       'code' => 'BBD', 'symbol' => 'Bds$', 'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Bahamian Dollar',        'code' => 'BSD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Trinidad and Tobago Dollar','code' => 'TTD','symbol' => 'TT$', 'thousand_separator' => ',', 'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Cayman Islands Dollar',  'code' => 'KYD', 'symbol' => 'CI$',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Bermudian Dollar',       'code' => 'BMD', 'symbol' => 'BD$',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],

            // South America
            ['currency_name' => 'Brazilian Real',         'code' => 'BRL', 'symbol' => 'R$',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Argentine Peso',         'code' => 'ARS', 'symbol' => '$',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Chilean Peso',           'code' => 'CLP', 'symbol' => '$',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Colombian Peso',         'code' => 'COP', 'symbol' => '$',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Peruvian Sol',           'code' => 'PEN', 'symbol' => 'S/.',  'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Venezuelan Bolívar',     'code' => 'VES', 'symbol' => 'Bs.',  'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Uruguayan Peso',         'code' => 'UYU', 'symbol' => '$U',   'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Paraguayan Guaraní',     'code' => 'PYG', 'symbol' => '₲',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Bolivian Boliviano',     'code' => 'BOB', 'symbol' => 'Bs',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Guyanese Dollar',        'code' => 'GYD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Surinamese Dollar',      'code' => 'SRD', 'symbol' => '$',    'thousand_separator' => '.',  'decimal_separator' => ',', 'symbol_position' => 'prefix'],

            // -------- Oceania --------
            ['currency_name' => 'Australian Dollar',      'code' => 'AUD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'New Zealand Dollar',     'code' => 'NZD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Fijian Dollar',          'code' => 'FJD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Papua New Guinean Kina', 'code' => 'PGK', 'symbol' => 'K',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Samoan Tala',            'code' => 'WST', 'symbol' => 'T',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Tongan Paʻanga',         'code' => 'TOP', 'symbol' => 'T$',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'Vanuatu Vatu',           'code' => 'VUV', 'symbol' => 'Vt',   'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'suffix'],
            ['currency_name' => 'Solomon Islands Dollar', 'code' => 'SBD', 'symbol' => '$',    'thousand_separator' => ',',  'decimal_separator' => '.', 'symbol_position' => 'prefix'],
            ['currency_name' => 'CFP Franc',              'code' => 'XPF', 'symbol' => '₣',    'thousand_separator' => ' ',  'decimal_separator' => ',', 'symbol_position' => 'suffix'],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }

    /**
     * Install units of measure for the company.
     *
     *
     */
    public function installLanguages() : void
    {
        $languages = [
            ['name' => 'English (US)', 'icon' => 'us', 'locale_code' => 'en_US', 'iso_code' => 'en', 'url_code' => 'en', 'direction' => 'left_to_right', 'separator_format' => '[3,0]', 'decimal_separator' => '.', 'thousand_separator' => ',', 'first_day' => 'sunday', 'is_active' => true, 'is_reference' => true],
            ['name' => 'French / Français', 'icon' => 'fr', 'locale_code' => 'fr_FR', 'iso_code' => 'fr', 'url_code' => 'fr', 'direction' => 'left_to_right', 'separator_format' => '[3,0]', 'decimal_separator' => ',', 'thousand_separator' => '.', 'first_day' => 'monday', 'is_active' => false],
            ['name' => 'Swahili / Kiswahili', 'icon' => '', 'locale_code' => 'sw', 'iso_code' => 'sw', 'url_code' => 'sw', 'direction' => 'left_to_right', 'separator_format' => '[3,0]', 'decimal_separator' => '.', 'thousand_separator' => ',', 'first_day' => 'monday', 'is_active' => false],
            ['name' => 'Hindi / हिंदी', 'icon' => '', 'locale_code' => 'hi_IN', 'iso_code' => 'hi', 'url_code' => 'hi', 'direction' => 'left_to_right', 'separator_format' => '[]', 'decimal_separator' => '.', 'thousand_separator' => ',', 'first_day' => 'sunday', 'is_active' => false],
            ['name' => 'Arabic / الْعَرَبيّة', 'icon' => 'sa', 'locale_code' => 'ar_AR', 'iso_code' => 'ar', 'url_code' => 'ar', 'direction' => 'right_to_left', 'separator_format' => '[3,0]', 'decimal_separator' => '.', 'thousand_separator' => ',', 'first_day' => 'saturday', 'is_active' => false],
            // ['name' => 'Japanese / 日本語', 'icon' => 'jp', 'locale_code' => 'ja_JP', 'iso_code' => 'ja', 'url_code' => 'ja', 'direction' => 'left_to_right', 'separator_format' => '[3,0]', 'decimal_separator' => '.', 'thousand_separator' => ',', 'first_day' => 'sunday', 'is_active' => false],
            // ['name' => 'Russian / русский язык', 'icon' => 'ru', 'locale_code' => 'ru_RU', 'iso_code' => 'ru', 'url_code' => 'ru', 'direction' => 'left_to_right', 'separator_format' => '[3,0]', 'decimal_separator' => ',', 'thousand_separator' => '', 'first_day' => 'monday', 'is_active' => false],
            // Add other languages similarly
        ];

        foreach ($languages as $language) {
            Language::create( $language);
        }

    }

}
