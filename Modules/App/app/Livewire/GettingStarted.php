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
    public $currentCountry = 'KE', $currentLanguage = 'en', $currentCurrency = 'KES', $countriesOptions = [], $currenciesOptions = [], $languagesOptions, $rolesOptions;
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
        'website' => 'required|url',
        'role' => 'required|string',
    ];

    public function mount(){

        $this->currentCountry = 'KE';
        $this->currenciesOptions = Currency::all();
        $this->languagesOptions = Language::all();

        $types = [
            ['id' => 'hotel', 'label' => __('Hotels')],
            ['id' => 'motel', 'label' => __('Motels')],
            ['id' => 'serviced-apartment', 'label' => __('Serviced Apartments & Vacation Rentals')],
            ['id' => 'guesthouse', 'label' => __('Guesthouses & Lodges')],
        ];
        $this->typesOptions = toSelectOptions($types, 'id', 'label');

        $roles = [
            ['id' => 'owner', 'label' => __('Owner')],
            ['id' => 'manager', 'label' => __('Hotel/General Manager')],
            ['id' => 'front-desk', 'label' => __('Front Desk / Receptionist')],
            ['id' => 'maintenance-staff', 'label' => __('Maintenance Staff')],
            ['id' => 'accountant', 'label' => __('Accountant')],
        ];
        $this->rolesOptions = toSelectOptions($roles, 'id', 'label');

        $this->countriesOptions = Country::all();
    }

    public function render()
    {
        return view('app::livewire.getting-started')
        ->extends('layouts.auth')->section('page_content');
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
