<?php

namespace Modules\ChannelManager\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Form\Button\ActionBarButton;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Traits\Form\Button\ActionBarButton as ActionBarButtonTrait;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;

class GuestForm extends LightWeightForm
{
    public $guest;
    public $name, $email, $phone, $inputId, $avatar, $company_name, $street, $street2, $zip, $city, $country, $nationality, $identificationType = 'id-card', $identification, $birthday, $gender, $job, $workAddress, $monthlyIncome, $type = "individual", $lease, $language, $timezone = 'eat';
    public $nextOfKin, $kinEmail, $kinPhone, $kinAddress;
    public $property, $unit, $monthlyRent = 0, $depositAmount = 0, $leaseTerm, $defaultLeaseTerm = "Monthly", $duration = 0, $depositPercentage = 100, $leaseStatus = 'pending';
    public bool $isLinked = false;
    public array $languageOptions = [], $timezoneOptions = [], $countriesOptions = [], $genderOptions = [], $identificationOptions = [], $propertiesOptions = [], $unitsOptions = [], $leaseTermsOptions = [];

    protected $rules = [
        'lease' => 'required|exists:leases,id',
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:tenants,email',
        'phone' => 'required|string|max:20|regex:/^\+?[0-9\s\-]+$/',
        'photo' => 'nullable|image|max:2048', // Ensure uploaded file is an image (max 2MB)
        'job' => 'nullable|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'workAddress' => 'nullable|string|max:500',
        'monthlyIncome' => 'nullable|numeric|min:0',
        'street' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'zip' => 'nullable|string|max:20',
        'nationality' => 'required|string|max:100',
        'identificationType' => 'required|string|in:passport,id-card,driver-license,resident-permit',
        'identification' => 'required|string|max:100|unique:tenants,identification',
        'birthday' => 'required|date|before:today',
        'gender' => 'required|string|in:male,female,other',
        'type' => 'required|string|in:individual,company',
        'nextOfKin' => 'nullable|string|max:255',
        'kinEmail' => 'nullable|email|max:255',
        'kinPhone' => 'nullable|string|max:20|regex:/^\+?[0-9\s\-]+$/',
        'kinAddress' => 'nullable|string|max:500',
        'property' => 'required|integer|exists:properties,id',
        'unit' => 'required|integer|exists:property_units,id',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate' => 'required|date|after:startDate',
        'monthlyRent' => 'required|numeric|min:1',
        'depositAmount' => 'required|numeric|min:1'
    ];


    public function mount($guest = null){
        if($guest){
            $this->guest = $guest;
            $this->hasPhoto = true;
            $this->image_path = $guest->avatar;
            $this->name = $guest->name;
            $this->email = $guest->email;
            $this->phone = $guest->phone;
            $this->job = $guest->job ?? null;
            $this->company_name = $guest->company_name ?? null;
            $this->workAddress = $guest->company_address ?? null;
            $this->monthlyIncome = $guest->monthly_income ?? null;
            $this->street = $guest->street ?? null;
            $this->city = $guest->city ?? null;
            $this->zip = $guest->zip ?? null;
            $this->country = $guest->country_id ?? null;
            $this->nationality = $guest->nationality_id ?? null;
            $this->identificationType = $guest->identification_type ?? null;
            $this->identification = $guest->identification ?? null;
            $this->birthday = $guest->birthday ?? null;
            $this->gender = $guest->gender ?? null;
            $this->type = $guest->type ?? null;
            $this->nextOfKin = $guest->kin_name ?? null;
            $this->kinEmail = $guest->kin_email ?? null;
            $this->kinPhone = $guest->kin_phone ?? null;
            $this->kinAddress = $guest->kin_address ?? null;

            $genders = [
                ['id' => 'male', 'label' => __('Male')],
                ['id' => 'female', 'label' => __('Female')],
            ];
            $this->genderOptions = toSelectOptions($genders, 'id', 'label');

            $identifications = [
                ['id' => 'id-card', 'label' => __('National ID Card')],
                ['id' => 'passport', 'label' => __('Passport')],
                ['id' => 'driver-license', 'label' => __('Driver’s License')],
                ['id' => 'residence-permit', 'label' => __('Residence Permit')],
            ];
            $this->identificationOptions = toSelectOptions($identifications, 'id', 'label');

            $this->languageOptions = toSelectOptions(Language::all(), 'id', 'name');
            $timezones = [
                ['id' => 'utc', 'label' => 'UTC (Coordinated Universal Time)'],
                ['id' => 'gst', 'label' => 'Greenwich Standard Time (GST) - United Kingdom'],
                ['id' => 'wet', 'label' => 'Western European Time (WET) - Ireland, Portugal'],
                ['id' => 'cet', 'label' => 'Central European Time (CET) - Austria, Belgium, France, Germany, Italy, Luxembourg, Netherlands, Spain, Switzerland'],
                ['id' => 'eet', 'label' => 'Eastern European Time (EET) - Bulgaria, Cyprus, Estonia, Finland, Greece, Hungary, Latvia, Lithuania, Malta, Romania'],
                ['id' => 'fnt', 'label' => 'Fernando de Noronha Time (FNT) - Brazil (Fernando de Noronha)'],
                ['id' => 'brt', 'label' => 'Brasília Time (BRT) - Brazil'],
                ['id' => 'uat', 'label' => 'Uruguay Time (UYT) - Uruguay'],
                ['id' => 'art', 'label' => 'Argentina Time (ART) - Argentina'],
                ['id' => 'clt', 'label' => 'Chile Time (CLT) - Chile'],
                ['id' => 'pyt', 'label' => 'Paraguay Time (PYT) - Paraguay'],
                ['id' => 'bost', 'label' => 'Bolivia Time (BOST) - Bolivia'],
                ['id' => 'pht', 'label' => 'Philippine Time (PHT) - Philippines'],
                ['id' => 'aest', 'label' => 'Australian Eastern Standard Time (AEST) - Queensland, New South Wales, Victoria, ACT, Tasmania'],
                ['id' => 'acst', 'label' => 'Australian Central Standard Time (ACST) - South Australia, Northern Territory'],
                ['id' => 'awst', 'label' => 'Australian Western Standard Time (AWST) - Western Australia'],
                ['id' => 'nzt', 'label' => 'New Zealand Time (NZT) - New Zealand'],
                ['id' => 'chast', 'label' => 'Chatham Standard Time (CHAST) - Chatham Islands, New Zealand'],
                ['id' => 'sst', 'label' => 'Samoa Standard Time (SST) - Samoa, American Samoa'],
                ['id' => 'cst', 'label' => 'China Standard Time (CST) - China'],
                ['id' => 'jst', 'label' => 'Japan Standard Time (JST) - Japan'],
                ['id' => 'kst', 'label' => 'Korea Standard Time (KST) - South Korea, North Korea'],
                ['id' => 'ist', 'label' => 'Indian Standard Time (IST) - India'],
                ['id' => 'slt', 'label' => 'Sri Lanka Time (SLT) - Sri Lanka'],
                ['id' => 'myt', 'label' => 'Malaysia Time (MYT) - Malaysia'],
                ['id' => 'sgt', 'label' => 'Singapore Time (SGT) - Singapore'],
                ['id' => 'ict', 'label' => 'Indochina Time (ICT) - Thailand, Vietnam, Laos, Cambodia'],
                ['id' => 'wib', 'label' => 'Western Indonesian Time (WIB) - Indonesia (Sumatra, Java, West Kalimantan)'],
                ['id' => 'wit', 'label' => 'Central Indonesian Time (WIT) - Indonesia (Sulawesi, Bali, Nusa Tenggara)'],
                ['id' => 'wita', 'label' => 'Eastern Indonesian Time (WITA) - Indonesia (Maluku, Papua)'],
                ['id' => 'aft', 'label' => 'Afghanistan Time (AFT) - Afghanistan'],
                ['id' => 'irt', 'label' => 'Iran Time (IRT) - Iran'],
                ['id' => 'pst', 'label' => 'Pakistan Standard Time (PKT) - Pakistan'],
                ['id' => 'ast', 'label' => 'Arabia Standard Time (AST) - Saudi Arabia, UAE, Oman, Qatar, Bahrain, Kuwait'],
                ['id' => 'mst', 'label' => 'Moscow Standard Time (MSK) - Russia (Moscow)'],
                ['id' => 'azt', 'label' => 'Azerbaijan Time (AZT) - Azerbaijan'],
                ['id' => 'gst', 'label' => 'Georgia Standard Time (GET) - Georgia'],
                ['id' => 'amt', 'label' => 'Armenia Time (AMT) - Armenia'],
                ['id' => 'trt', 'label' => 'Turkey Time (TRT) - Turkey'],
                ['id' => 'eet', 'label' => 'Israel Standard Time (IST) - Israel'],
                ['id' => 'eet', 'label' => 'Lebanon Standard Time (EET) - Lebanon'],
                ['id' => 'eet', 'label' => 'Syria Standard Time (EET) - Syria'],
                ['id' => 'eet', 'label' => 'Jordan Standard Time (EET) - Jordan'],
                ['id' => 'eet', 'label' => 'Palestine Standard Time (EET) - Palestine'],
                ['id' => 'eet', 'label' => 'Iraq Standard Time (AST) - Iraq'],
                ['id' => 'eet', 'label' => 'Gaza Standard Time (EET) - Gaza Strip'],
                ['id' => 'eat', 'label' => 'East Africa Time (EAT) - Kenya, Tanzania, Uganda, Ethiopia'],
                ['id' => 'cat', 'label' => 'Central Africa Time (CAT) - Cameroon, Chad, Congo, Rwanda, Burundi'],
                ['id' => 'wat', 'label' => 'West Africa Time (WAT) - Nigeria, Niger, Ghana, Senegal'],
                ['id' => 'gmt', 'label' => 'Greenwich Mean Time (GMT) - Senegal, Gambia, Guinea-Bissau, Guinea, Sierra Leone, Liberia, Mali, Côte d\'Ivoire, Burkina Faso, Ghana, Togo'],
                ['id' => 'cvst', 'label' => 'Cape Verde Standard Time (CVT) - Cape Verde'],
                ['id' => 'sast', 'label' => 'South Africa Standard Time (SAST) - South Africa, Lesotho, Eswatini'],
                ['id' => 'mut', 'label' => 'Mauritius Time (MUT) - Mauritius'],
                ['id' => 'sct', 'label' => 'Seychelles Time (SCT) - Seychelles'],
                ['id' => 'pst', 'label' => 'Pacific Standard Time (PST) - USA (California, Washington, Oregon)'],
                ['id' => 'mst', 'label' => 'Mountain Standard Time (MST) - USA (Colorado, Idaho, Montana, New Mexico, Utah, Wyoming)'],
                ['id' => 'cst', 'label' => 'Central Standard Time (CST) - USA (Illinois, Indiana, Michigan, Minnesota, Missouri, Texas, Wisconsin)'],
                ['id' => 'est', 'label' => 'Eastern Standard Time (EST) - USA (New York, Pennsylvania, Georgia, Florida, Ohio)'],
                ['id' => 'ast', 'label' => 'Atlantic Standard Time (AST) - Canada (Nova Scotia, New Brunswick, Prince Edward Island, Newfoundland and Labrador)'],
                ['id' => 'hst', 'label' => 'Hawaii-Aleutian Standard Time (HST) - USA (Hawaii)'],
                ['id' => 'akst', 'label' => 'Alaska Standard Time (AKST) - USA (Alaska)'],
                ['id' => 'chut', 'label' => 'Chuuk Time (CHUT) - Federated States of Micronesia (Chuuk)'],
                ['id' => 'kst', 'label' => 'Kosrae Time (KST) - Federated States of Micronesia (Kosrae)'],
                ['id' => 'pgt', 'label' => 'Papua New Guinea Time (PGT) - Papua New Guinea'],
                ['id' => 'sbt', 'label' => 'Solomon Islands Time (SBT) - Solomon Islands'],
                ['id' => 'vut', 'label' => 'Vanuatu Time (VUT) - Vanuatu'],
                ['id' => 'nct', 'label' => 'New Caledonia Time (NCT) - New Caledonia'],
                ['id' => 'fjt', 'label' => 'Fiji Time (FJT) - Fiji'],
                ['id' => 'tvt', 'label' => 'Tuvalu Time (TVT) - Tuvalu'],
                ['id' => 'wft', 'label' => 'Wallis and Futuna Time (WFT) - Wallis and Futuna'],
                ['id' => 'nft', 'label' => 'Norfolk Time (NFT) - Norfolk Island'],
                ['id' => 'aft', 'label' => 'Afghanistan Time (AFT) - Afghanistan'],
                ['id' => 'cct', 'label' => 'Cocos Islands Time (CCT) - Cocos (Keeling) Islands'],
                ['id' => 'mht', 'label' => 'Marshall Islands Time (MHT) - Marshall Islands'],
                ['id' => 'krat', 'label' => 'Krasnoyarsk Time (KRAT) - Russia (Krasnoyarsk)'],
                ['id' => 'yakt', 'label' => 'Yakutsk Time (YAKT) - Russia (Yakutsk)'],
                ['id' => 'vlat', 'label' => 'Vladivostok Time (VLAT) - Russia (Vladivostok)'],
                ['id' => 'magt', 'label' => 'Magadan Time (MAGT) - Russia (Magadan)'],
                ['id' => 'anat', 'label' => 'Anadyr Time (ANAT) - Russia (Anadyr)'],
                ['id' => 'pet', 'label' => 'Peru Time (PET) - Peru'],
                ['id' => 'clt', 'label' => 'Chile Time (CLT) - Chile'],
                ['id' => 'cct', 'label' => 'Cuba Time (CST) - Cuba'],
                ['id' => 'ect', 'label' => 'Ecuador Time (ECT) - Ecuador'],
                ['id' => 'gst', 'label' => 'Guyana Time (GYT) - Guyana'],
                ['id' => 'srt', 'label' => 'Suriname Time (SRT) - Suriname'],
                ['id' => 'vst', 'label' => 'Venezuela Time (VET) - Venezuela'],
            ];
            $this->timezoneOptions = toSelectOptions($timezones, 'id', 'label');

            $this->countriesOptions = toSelectOptions(Country::all()->sortBy('common_name'), 'id', 'common_name');

            $this->startDate = Carbon::now()->format('Y-m-d');
            $this->endDate = Carbon::now()->addYears(1)->format('Y-m-d');

        }
    }
    public function capsules() : array
    {
        return [
            Capsule::make('unit', __('Unit'), __('Unit rented by the tenant'), 'link', 'fa fa-home-user', route('properties.units.lists'), []),
            // Capsule::make('inventory-items', __('Inventory Items'), __('Inventory items assigned to the property'), 'link', 'fa fa-warehouse'),
            Capsule::make('lease', __("Tenant's Lease"), __('Lease linked to the tenant'), 'link', 'bi bi-file-earmark-post-fill',   route('guests.lists'), []),
            Capsule::make('payment-history', __("Payment History"), __('Payment made by the tenant'), 'link', 'bi bi-wallet2',   route('guests.lists'), []),
            Capsule::make('documents', __("Tenant Documents"), __('Tenants documents'), 'link', 'bi bi-file-earmark-pdf',   route('guests.lists'), []),
            Capsule::make('requests', __("Maintenance Requests"), __('Maintenance Requests made by the  tenant'), 'link', 'bi bi-wrench',   route('guests.lists'), []),
        ];
    }
    public function tabs() : array
    {
        return [
            Tabs::make('general',__('General Information'),),
            Tabs::make('units',__('Units'), null),
            Tabs::make('gallery',__('Gallery'), null),
            // Tabs::make('front-desk',__('Front Desk'), null, true),
        ];
    }

    public function groups() : array
    {
        return [
            Group::make('general',__("Personal Information"), 'general'),
            Group::make('lease',__("Lease & Property Information"), 'units')->component('app::form.tab.group.special.journey'),
            // Group::make('general',__("General"), 'general')->component('app::form.tab.group.light'),
            Group::make('contacts',__("Contact Details"), 'general'),
            Group::make('address',__("Address Details"), 'general'),
            Group::make('financials',__("Employment & Financial Details"), 'units'),
            Group::make('preferences',__("Other Information & Preferences"), 'units'),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Arden BOUET'))->component('app::form.input.ke-title'),
            // Input::make('job', "", 'text', 'job', 'top-title', 'none', 'none', __('e.g. Software Engineer'))->component('app::form.input.subtitle'),
            // Personal Informations
            Input::make('gender', __('Gender'), 'select', 'gender', 'right', 'none', 'general', null, null, $this->genderOptions),
            Input::make('birthday', __('Date of Birth'), 'date', 'birthday', 'right', 'none', 'general', null, null),
            Input::make('nationality', __('Nationality'), 'select', 'nationality', 'right', 'none', 'general', null, null, $this->countriesOptions),
            Input::make('identificationType', __('Identification Type'), 'select', 'identificationType', 'right', 'none', 'general', null, null, $this->identificationOptions),
            Input::make('identification', __('Identification Number'), 'text', 'identification', 'right', 'none', 'general', null, null),
            // Contacts
            Input::make('email', __('Email'), 'email', 'email', 'left', 'none', 'contacts', __('e.g. email@yourcompany.com')),
            Input::make('phone', __('Phone'), 'tel', 'phone', 'left', 'none', 'contacts', __('e.g. +254745908026')),
            Input::make('nextOfKin', __('Next of kin'), 'text', 'nextOfKin', 'right', 'none', 'contacts', __("e.g. Arden BOUET's brother")),
            Input::make('kinEmail', __('Next of kin email'), 'text', 'kinEmail', 'right', 'none', 'contacts', __('e.g. email@yourcompany.com')),
            Input::make('kinPhone', __('Next of kin phone'), 'text', 'kinPhone', 'right', 'none', 'contacts', __('e.g. +254745908026')),
            Input::make('kinAddress', __('Next of kin address'), 'text', 'kinAddress', 'right', 'none', 'contacts', __('Kipkabus Rd, Ngara, Nairobi, Kenya')),
            // Address
            Input::make('address', null, 'select', 'address', 'left', 'general', 'address', "")->component('app::form.input.select.address'),
            // Employment & Financial
            Input::make('job', __('Occupation'), 'text', 'job', 'right', 'none', 'financials', __('e.g. Software Engineer'), null),
            Input::make('companyName', __('Employer Name'), 'text', 'companyName', 'right', 'none', 'financials', __('e.g. Koverae Technologies'), null),
            Input::make('workAddress', __('Work Address'), 'text', 'workAddress', 'right', 'none', 'financials', __('Moi Avenue, Nairobi, Kenya'), null),
            Input::make('monthlyIncome', __('Monthly Income'), 'number', 'monthlyIncome', 'right', 'none', 'financials', format_currency(45000), null),

            // Preferences
            Input::make('vehiclePlate', __('Vehicle Plate'), 'number', 'vehiclePlate', 'right', 'none', 'preferences', __('e.g. KDE 482M'), null),
            Input::make('language', 'Language', 'select', 'language', 'none', 'preferences', 'preferences', "", "", $this->languageOptions),
            Input::make('timezone', 'Timezone', 'select', 'timezone', 'nope', 'preferences', 'preferences', "", "", $this->timezoneOptions),

        ];
    }
}
