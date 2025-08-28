<?php

namespace Modules\ChannelManager\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\RevenueManager\Services\Pricing\RateService;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;

class GuestForm extends LightWeightForm
{
    use WithFileUploads;

    /** Model */
    public ?Guest $guest = null;

    /** Core identity */
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $gender = null;                     // male|female|other
    public ?string $birthday = null;                   // Y-m-d
    public ?string $nationality = null;                // country id (string) for consistency with your helpers

    /** ID docs */
    public string $identificationType = 'passport';    // passport|id-card|driver-license|residence-permit
    public ?string $identification = null;

    /** Address & contact */
    public ?string $street = null;
    public ?string $street2 = null;
    public ?string $city = null;
    public ?string $zip = null;
    public ?string $country = null;                    // country id (string)
    public ?string $language = null;                   // language id
    public string $timezone = 'eat';                   // UI-friendly code

    /** Employment (optional for hotels but kept as optional fields) */
    public ?string $job = null;
    public ?string $company_name = null;
    public ?string $workAddress = null;
    public ?float  $monthlyIncome = null;

    /** Next of kin (optional) */
    public ?string $nextOfKin = null;
    public ?string $kinEmail = null;
    public ?string $kinPhone = null;
    public ?string $kinAddress = null;

    /** Avatar upload */
    public $photo = null;                              // Livewire temp file
    public ?string $avatar = null;                     // stored path

    /** UI options */
    public array $genderOptions = [];
    public array $identificationOptions = [];
    public array $languageOptions = [];
    public array $timezoneOptions = [];
    public array $countriesOptions = [];
    protected $rateService, $bookingService;

    /* ----------------------------- Lifecycle ----------------------------- */



    public function boot(RateService $rateService, BookingService $bookingService){
        $this->rateService = $rateService;
        $this->bookingService = $bookingService;
    }

    public function mount($guest = null): void
    {
        $this->hasPhoto = true;
        if($guest){
            
            $this->guest = $guest;
            $this->image_path = $guest->avatar;
            $this->hydrateOptions();
            $this->fillFromGuest($this->guest);

            // Reasonable defaults when creating
            if (!$this->birthday) {
                $this->birthday = null; // let user pick; enforce "before:today" in rules
            }
            if (!$this->language && $this->languageOptions) {
                $this->language = $this->languageOptions[0]['id'] ?? null;
            }
            if (!$this->timezone) {
                $this->timezone = 'eat'; // East Africa Time for Nairobi
            }
        }
    }

    protected function hydrateOptions(): void
    {
        // Simple, clean enums
        $this->genderOptions = toSelectOptions([
            ['id' => 'male',   'label' => __('Male')],
            ['id' => 'female', 'label' => __('Female')],
            ['id' => 'other',  'label' => __('Other')],
        ], 'id', 'label');

        $this->identificationOptions = toSelectOptions([
            ['id' => 'passport',         'label' => __('Passport')],
            ['id' => 'id-card',          'label' => __('National ID Card')],
            ['id' => 'driver-license',   'label' => __('Driver’s License')],
            ['id' => 'residence-permit', 'label' => __('Residence Permit')],
        ], 'id', 'label');

        // Keep these light & sorted
        $this->languageOptions  = toSelectOptions(Language::query()->orderBy('name')->get(), 'id', 'name');
        $this->countriesOptions = toSelectOptions(Country::query()->orderBy('common_name')->get(), 'id', 'common_name');

        // Curated timezones for hotel ops (add more if you need)
        $tz = [
            ['id' => 'eat', 'label' => 'East Africa Time (EAT)'],
            ['id' => 'cat', 'label' => 'Central Africa Time (CAT)'],
            ['id' => 'wat', 'label' => 'West Africa Time (WAT)'],
            ['id' => 'gmt', 'label' => 'Greenwich Mean Time (GMT)'],
            ['id' => 'sast','label' => 'South Africa Time (SAST)'],
            ['id' => 'cet', 'label' => 'Central Europe Time (CET)'],
            ['id' => 'utc', 'label' => 'UTC'],
        ];
        $this->timezoneOptions = toSelectOptions($tz, 'id', 'label');
    }

    protected function fillFromGuest($g): void
    {
        if (!$g) return;

        $this->avatar            = $g->avatar;
        $this->name              = $g->name;
        $this->email             = $g->email;
        $this->phone             = $g->phone;

        $this->job               = $g->job;
        $this->company_name      = $g->company_name;
        $this->workAddress       = $g->company_address;
        $this->monthlyIncome     = $g->monthly_income;

        $this->street            = $g->street;
        $this->street2           = $g->street2 ?? null;
        $this->city              = $g->city;
        $this->zip               = $g->zip;
        $this->country           = (string)($g->country_id ?? '');
        $this->nationality       = (string)($g->nationality_id ?? '');

        $this->identificationType= $g->identity_proof ?: 'passport';
        $this->identification    = $g->identity;

        $this->birthday          = optional($g->birthday)->format('Y-m-d');
        $this->gender            = $g->gender ?: null;
        $this->language          = (string)($g->language_id ?? '');
        $this->timezone          = $g->timezone ?? $this->timezone;

        $this->nextOfKin         = $g->kin_name;
        $this->kinEmail          = $g->kin_email;
        $this->kinPhone          = $g->kin_phone;
        $this->kinAddress        = $g->kin_address;
    }

    /* ------------------------------ Validation ------------------------------ */

    public function rules(): array
    {
        $guestId = $this->guest?->id;

        return [
            'name'              => ['required','string','max:255'],
            'email'             => ['nullable','email','max:255', Rule::unique('guests','email')->ignore($guestId)],
            'phone'             => ['required','string','max:20','regex:/^\+?[0-9\s\-]{7,20}$/'],
            'photo'             => ['nullable','image','max:2048'],

            'gender'            => ['nullable','in:male,female,other'],
            'birthday'          => ['nullable','date','before:today'],
            'nationality'       => ['nullable','string','max:100'],
            'language'          => ['nullable','string'],
            'timezone'          => ['nullable','string'],

            'identificationType'=> ['required','in:passport,id-card,driver-license,residence-permit'],
            'identification'    => ['required','string','max:100', Rule::unique('guests','identity')->ignore($guestId)],

            'street'            => ['nullable','string','max:255'],
            'street2'           => ['nullable','string','max:255'],
            'city'              => ['nullable','string','max:100'],
            'zip'               => ['nullable','string','max:20'],
            'country'           => ['nullable','string'],

            'job'               => ['nullable','string','max:255'],
            'company_name'      => ['nullable','string','max:255'],
            'workAddress'       => ['nullable','string','max:500'],
            'monthlyIncome'     => ['nullable','numeric','min:0'],

            'nextOfKin'         => ['nullable','string','max:255'],
            'kinEmail'          => ['nullable','email','max:255'],
            'kinPhone'          => ['nullable','string','max:20','regex:/^\+?[0-9\s\-]{7,20}$/'],
            'kinAddress'        => ['nullable','string','max:500'],
        ];
    }

    /* ------------------------------ UI Schema ------------------------------ */
    public function capsules(): array
    {
        // Prefer hotel routes when available; gracefully fall back.
        $guestId        = $this->guest?->id;
        $guestListRoute = Route::has('guests.lists') ? route('guests.lists') : url('/');

        $roomRoute      = Route::has('rooms.lists')
            ? route('rooms.lists')
            : (Route::has('properties.units.lists') ? route('properties.units.lists') : $guestListRoute);

        $bookingRoute   = Route::has('bookings.lists')
            ? route('bookings.lists')
            : $guestListRoute;

        $paymentsRoute  = $guestId && Route::has('guests.payments')
            ? route('guests.payments', ['guest' => $guestId])
            : $guestListRoute;

        $folioRoute     = $guestId && Route::has('guests.folio')
            ? route('guests.folio', ['guest' => $guestId])
            : $paymentsRoute;

        $docsRoute      = $guestId && Route::has('guests.documents')
            ? route('guests.documents', ['guest' => $guestId])
            : $guestListRoute;

        $requestsRoute  = $guestId && Route::has('guests.requests')
            ? route('guests.requests', ['guest' => $guestId])
            : (Route::has('maintenance.requests') ? route('maintenance.requests') : $guestListRoute);

        // If we're creating a new guest (no ID yet), show a minimal, helpful set.
        if (!$guestId) {
            return [
                Capsule::make('rooms',     __('Rooms'),            __('Browse rooms & availability'),   'link', 'fa fa-door-closed',      $roomRoute,     []),
                Capsule::make('bookings',  __('Bookings'),         __('Manage guest bookings'),         'link', 'bi bi-calendar2-check',  $bookingRoute,  []),
                Capsule::make('guests',    __('Guests'),           __('Back to guest list'),            'link', 'bi bi-people',           $guestListRoute,[]),
            ];
        }

        // Full set for an existing guest profile.
        return [
            Capsule::make('room',        __('Room'),             __('Room currently assigned'),        'link', 'fa fa-door-closed',      $roomRoute,     []),
            Capsule::make('stays',       __('Stays / Bookings'), __('Past & upcoming stays'),          'link', 'bi bi-calendar2-check',  $bookingRoute,  []),
            Capsule::make('folio',       __('Folio'),            __('Charges, payments & balance'),    'link', 'bi bi-receipt',          $folioRoute,    []),
            Capsule::make('payments',    __('Payment History'),  __('Payments made by the guest'),     'link', 'bi bi-wallet2',          $paymentsRoute, []),
            Capsule::make('documents',   __('Guest Documents'),  __('IDs, vouchers, signed forms'),    'link', 'bi bi-file-earmark-pdf', $docsRoute,     []),
            Capsule::make('requests',    __('Service Requests'), __('Housekeeping / maintenance'),     'link', 'bi bi-wrench',           $requestsRoute, []),
        ];
    }


    public function tabs(): array
    {
        return [
            Tabs::make('general',   __('General Information')),
            Tabs::make('contacts',  __('Contacts')),
            Tabs::make('preferences',__('Preferences')),
        ];
    }

    public function groups(): array
    {
        return [
            Group::make('general',    __("Personal Information"), 'general'),
            Group::make('lease',__("Journey Information"), 'units')->component('app::form.tab.group.special.journey'),
            Group::make('documents',  __("Identification"),       'general'),
            Group::make('address',    __("Address Details"),      'contacts'),
            Group::make('kin',        __("Next of Kin"),          'contacts'),
            Group::make('work',       __("Occupation (Optional)"),'preferences'),
            Group::make('prefs',      __("Language & Timezone"),  'preferences'),
        ];
    }

    public function inputs(): array
    {
        return [
            // Personal
            Input::make('name',            __("Name"),                  'text',  'name',            'top-title', 'none', 'none',   __('e.g. Jane Doe'))->component('app::form.input.ke-title'),
            Input::make('gender',          __('Gender'),                'select','gender',          'right',     'none', 'general',   null, null, $this->genderOptions),
            Input::make('birthday',        __('Date of Birth'),         'date',  'birthday',        'right',     'none', 'general'),
            Input::make('nationality',     __('Nationality'),           'select','nationality',     'right',     'none', 'general',   null, null, $this->countriesOptions),

            // Identification
            Input::make('identificationType', __('Identification Type'),'select','identificationType','left',    'none', 'documents', null, null, $this->identificationOptions),
            Input::make('identification',  __('Identification Number'), 'text',  'identification',   'right',    'none', 'documents'),

            // Contacts & Address
            Input::make('email',     __('Email'),          'email','email', 'left',  'none', 'address',  __('e.g. guest@email.com')),
            Input::make('phone',     __('Phone'),          'tel',  'phone', 'left',  'none', 'address',  __('e.g. +254712345678')),
            Input::make('street',    __('Street'),         'text', 'street','left',  'none', 'address'),
            Input::make('street2',   __('Street 2'),       'text', 'street2','left', 'none', 'address'),
            Input::make('city',      __('City'),           'text', 'city',  'left',  'none', 'address'),
            Input::make('zip',       __('ZIP / Postal'),   'text', 'zip',   'left',  'none', 'address'),
            Input::make('country',   __('Country'),        'select','country','left','none', 'address', null, null, $this->countriesOptions),

            // Next of kin
            Input::make('nextOfKin', __('Next of Kin'),        'text', 'nextOfKin','left','none','kin'),
            Input::make('kinEmail',  __('Next of Kin Email'),  'email','kinEmail', 'left','none','kin'),
            Input::make('kinPhone',  __('Next of Kin Phone'),  'tel',  'kinPhone', 'left','none','kin'),
            Input::make('kinAddress',__('Next of Kin Address'),'text', 'kinAddress','left','none','kin'),

            // Work (optional)
            Input::make('job',          __('Occupation'),     'text',   'job',          'left','none','work', __('e.g. Software Engineer')),
            Input::make('company_name', __('Employer Name'),  'text',   'company_name', 'left','none','work', __('e.g. Koverae Technologies')),
            Input::make('workAddress',  __('Work Address'),   'text',   'workAddress',  'left','none','work', __('Moi Avenue, Nairobi')),
            Input::make('monthlyIncome',__('Monthly Income'), 'number', 'monthlyIncome','left','none','work', format_currency(0)),

            // Preferences
            Input::make('language', __('Language'),  'select', 'language', 'left','none','prefs', null, null, $this->languageOptions),
            Input::make('timezone', __('Timezone'),  'select', 'timezone', 'left','none','prefs', null, null, $this->timezoneOptions),
        ];
    }

    /* ------------------------------ Handlers ------------------------------ */

    public function updatedPhoto(): void
    {
        $this->validateOnly('photo');

        // If creating, postpone writing until saved
        if (!$this->guest) return;

        $filename = "guest_{$this->guest->id}.png";
        $path = $this->photo->storePubliclyAs('avatars', $filename, 'public');

        $this->guest->update(['avatar' => $path]);
        $this->avatar = $path;

        session()->flash('message', __('Avatar updated successfully!'));
        $this->dispatch('guest-avatar-updated', id: $this->guest->id, path: $path);
    }

    #[\Livewire\Attributes\On('create-guest')]
    public function saveGuest()
    {
        $data = $this->validate();

        $guest = Guest::create([
            'company_id'      => current_company()->id,
            'name'            => $data['name'],
            'email'           => $data['email'] ?? null,
            'phone'           => $data['phone'],
            'job'             => $this->job,
            'company_name'    => $this->company_name,
            'company_address' => $this->workAddress,
            'monthly_income'  => $this->monthlyIncome,
            'street'          => $this->street,
            'street2'         => $this->street2,
            'city'            => $this->city,
            'zip'             => $this->zip,
            'country_id'      => $this->country ?: null,
            'nationality_id'  => $this->nationality ?: null,
            'identity_proof'  => $this->identificationType,
            'identity'        => $this->identification,
            'birthday'        => $this->birthday,
            'gender'          => $this->gender,
            'language_id'     => $this->language ?: null,
            'timezone'        => $this->timezone ?: 'eat',
            'kin_name'        => $this->nextOfKin,
            'kin_email'       => $this->kinEmail,
            'kin_phone'       => $this->kinPhone,
            'kin_address'     => $this->kinAddress,
        ]);

        // Handle avatar if present
        if ($this->photo) {
            $filename = "guest_{$guest->id}.png";
            $path = $this->photo->storePubliclyAs('avatars', $filename, 'public');
            $guest->update(['avatar' => $path]);
            $this->avatar = $path;
        }

        $this->dispatch('guest-created', id: $guest->id);
        return $this->redirect(route('guests.show', ['guest' => $guest->id]), navigate: true);
    }

    #[\Livewire\Attributes\On('update-guest')]
    public function updateGuest()
    {
        $this->validate();

        $this->guest->update([
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'job'             => $this->job,
            'company_name'    => $this->company_name,
            'company_address' => $this->workAddress,
            'monthly_income'  => $this->monthlyIncome,
            'street'          => $this->street,
            'street2'         => $this->street2,
            'city'            => $this->city,
            'zip'             => $this->zip,
            'country_id'      => $this->country ?: null,
            'nationality_id'  => $this->nationality ?: null,
            'identity_proof'  => $this->identificationType,  // ✅ consistent with create()
            'identity'        => $this->identification,
            'birthday'        => $this->birthday,
            'gender'          => $this->gender,
            'language_id'     => $this->language ?: null,
            'timezone'        => $this->timezone ?: 'eat',
            'kin_name'        => $this->nextOfKin,
            'kin_email'       => $this->kinEmail,
            'kin_phone'       => $this->kinPhone,
            'kin_address'     => $this->kinAddress,
        ]);

        $this->dispatch('guest-updated', id: $this->guest->id);
        return $this->redirect(route('guests.show', ['guest' => $this->guest->id]), navigate: true);
    }
}
