<?php

namespace Modules\App\Livewire;

use Livewire\Component;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;
use App\Models\Company\Company;
use App\Models\User;
use App\Rules\ReCaptcha;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Modules\App\Handlers\AppManagerHandler;

class GettingStarted extends Component
{
    public $currentCountry = 'KE', $currentLanguage = 'en', $currentCurrency = 'KES', $countriesOptions = [], $currenciesOptions = [], $languagesOptions, $rolesOptions;
    public array $typesOptions = [];
    public $name, $type, $language, $currency, $rooms, $city, $country, $website, $role;
    public $test = '';

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
            ['id' => 'owner', 'label' => __('Owner / Founder')],
            ['id' => 'manager', 'label' => __('Hotel Manager')],
            ['id' => 'front-desk', 'label' => __('Front Desk / Receptionist')],
            ['id' => 'maintenance-staff', 'label' => __('Maintenance Staff')],
            ['id' => 'accountant', 'label' => __('Accountant')],
        ];
        $this->rolesOptions = toSelectOptions($roles, 'id', 'label');

        $this->countriesOptions = Country::all();
    }

    public function render()
    {
        return view('app::livewire.getting-started');
    }

    public function getStarted(){
        
        $this->validate();
        
        $user = User::find(Auth::user()->id);

        $company = Company::create([
            'uuid' => Uuid::uuid4(),
            'owner_id' => $user->id,
            'name' => $this->name,
            'website_url' => $this->website,
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
}
