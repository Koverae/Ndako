<?php

namespace Modules\App\Livewire;

use Livewire\Component;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;
use App\Models\Company\Company;
use App\Models\Team\Team;
use App\Models\User;
use App\Rules\ReCaptcha;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Modules\App\Handlers\AppManagerHandler;
use Koverae\KoveraeBilling\Models\Plan;
use Koverae\KoveraeBilling\Models\PlanSubscriptionFeature;

class GettingStarted extends Component
{
    public $currentCountry = 'KE', $currentLanguage = 'en', $currentCurrency = 'KES', $countriesOptions = [], $citiesOptions = [], $currenciesOptions = [], $languagesOptions, $rolesOptions;
    public array $typesOptions = [];
    public $name, $type, $language, $currency, $rooms, $city, $country, $website, $role;
    public $test = '';
    public $plan, $billingCycle;

    protected $rules = [
        'name' => 'required|string|max:120',
        'type' => 'required|string|max:120',
        'language' => 'required|integer|exists:languages,id',
        'currency' => 'required|integer|exists:currencies,id',
        'rooms' => 'required|integer|min:1',
        'city' => 'required|string|max:50',
        'country' => 'required|integer|exists:countries,id',
        'website' => 'nullable|url|unique:companies,website',
        'role' => 'required|string',
    ];

    public function mount(){

        $this->currentCountry = 'KE';
        $this->currenciesOptions = Currency::whereIn('code', ['KES', 'UGX', 'TZS', 'RWF','USD', 'EUR', 'GBP', 'CNY'])->get();
        $this->languagesOptions = Language::whereIn('iso_code', ['en'])->get();

        $types = [
            ['id' => 'hotel',              'label' => __('Hotel')],
            ['id' => 'lodge',              'label' => __('Lodge')], // covers safari lodges/camps for now
            ['id' => 'guesthouse-bnb',     'label' => __('Guesthouse / B&B')],
            ['id' => 'hostel',             'label' => __('Hostel')],
            ['id' => 'serviced-apartment', 'label' => __('Serviced Apartment / Aparthotel')],
            ['id' => 'holiday-home',       'label' => __('Holiday Home / Villa')],
        ];
        $this->typesOptions = toSelectOptions($types, 'id', 'label');

        $roles = [
            ['id' => 'owner',         'label' => __('Owner')], // full access
            ['id' => 'manager',       'label' => __('General / Property Manager')], // ops + settings + reports
            ['id' => 'front-office',  'label' => __('Front Office (Reception & Concierge)')], // check-in/out, guests, payments
            ['id' => 'reservations',  'label' => __('Reservations Agent')], // holds & confirms bookings
            ['id' => 'housekeeping',  'label' => __('Housekeeping')], // room status & tasks
            ['id' => 'maintenance',   'label' => __('Maintenance Technician')], // work orders & status
            ['id' => 'accounting',    'label' => __('Accountant / Finance')], // invoices, refunds, reports
            ['id' => 'cashier',       'label' => __('POS Cashier')],
        ];
        $this->rolesOptions = toSelectOptions($roles, 'id', 'label');

        $this->countriesOptions = Country::whereIn('country_code', ['KE', 'UG', 'TZ', 'RW'])->get();
        $this->citiesOptions = [
            'KE' => [
                'Nairobi','Mombasa','Kisumu','Nakuru','Eldoret','Thika','Meru','Machakos',
                // 'Malindi','Kitale', 'Nyeri','Garissa','Embu','Kericho','Naivasha',
            ],
            'UG' => [
                'Kampala',
                // 'Gulu','Lira','Mbarara','Jinja','Arua','Mbale','Fort Portal','Masaka','Hoima','Soroti','Mityana',
            ],
            'TZ' => [
                'Dar es Salaam', 'Zanzibar City',
                // 'Dodoma','Mwanza','Arusha','Mbeya','Tanga','Morogoro','Tabora','Shinyanga','Kigoma','Songea','Sumbawanga',
            ],
            'RW' => [
                'Kigali',
                // 'Huye (Butare)','Muhanga (Gitarama)','Gicumbi (Byumba)', 'Rubavu (Gisenyi)','Rusizi (Cyangugu)','Musanze (Ruhengeri)', 'Karongi (Kibuye)','Nyagatare','Rwamagana',
            ],
            'ZM' => [
                'Lusaka',
                // 'Ndola','Kitwe','Kabwe','Chingola','Livingstone','Mufulira', 'Luanshya','Kasama','Chipata','Solwezi','Mongu',
            ],
        ];
    }

    public function render()
    {
        return view('app::livewire.getting-started')
        ->extends('layouts.auth')->section('page_content');
    }

    public function updatedCountry($value){
        $code = Country::find($value)->country_code;
        $this->currentCountry = $code;
    }
    public function updatedWebsite($value){
        if (Company::where('website', $value)->exists()) {
            $this->addError('website', __('This website is already registered.'));
        } else {
            $this->resetErrorBag('website');
        }
    }

    public function getStarted(){

        $this->validate();
        $user = User::find(Auth::user()->id);

        $team = Team::create([
            'user_id' => $user->id
        ]);
        $team->save();

        $plan = $this->getPlan();
        $subscription = $team->newSubscription(
            'main', // identifier tag of the subscription. If your application offers a single subscription, you might call this 'main' or 'primary'
             $plan, // Plan or PlanCombination instance your subscriber is subscribing to
             'Main subscription', // Human-readable name for your subscription
             'Customer main subscription', // Description
             null, // Start date for the subscription, defaults to now()
             'free' // Payment method service defined in config
        );

        // Attach features from the plan
        // $subscription->syncPlanFeatures($plan);

        // $team->update([
        //     ''
        // ]);

        $company = Company::create([
            'team_id' => $team->id,
            'owner_id' => $user->id,
            'name' => $this->name,
            'website' => $this->website,
            'city' => $this->city,
            'country_id' => $this->country,
            'industry' => $this->type,
            'size' => $this->rooms,
            'primary_interest' => 'manage_my_business',
            'default_currency_id' => $this->currency,
        ]);
        $company->save();

        // Install Modules
        $appManager = new AppManagerHandler;
        $appManager->installModules($company->id, $user->id);

        $user->update([
            'company_id' => $company->id,
            'current_company_id' => $company->id,
            'language_id' => $this->language
        ]);
        $user->save();

        $user->assignRole($this->role);
        $user->givePermissionTo('manage_kover_subscription');

        return redirect()->route('dashboard');

    }

    public function getplan(){

        // Determine the plan based on the number of rooms
        if ($this->rooms <= 20) {
            $plan = 'starter';
        } elseif ($this->rooms > 20) {
            $plan = 'spark';
        } else {
            $plan = 'enterprise';
        }

        // Check if the billing cycle is passed in the URL, else let the user choose
        $billingCycle = request()->query('billing_cycle', 'monthly');


        // Ensure billing cycle is valid
        if (!in_array($billingCycle, ['monthly', 'yearly'])) {
            $billingCycle = null; // Force user to select if not provided
        }

        if($plan !== 'starter'){
            $tag = $plan.'-'.$billingCycle;
            $plan = Plan::getByTag($tag);
        }else{
            $plan = Plan::getByTag('starter');
        }

        return $plan;
    }
}
