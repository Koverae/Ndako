<?php

namespace Modules\App\Livewire\Wizard;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Modules\App\Livewire\Components\Wizard\SimpleWizard;
use Modules\App\Livewire\Components\Wizard\Step;
use Modules\App\Livewire\Components\Wizard\StepPage;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyAmenity;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Settings\Models\Identity\IdentityVerification;
use Modules\Settings\Models\Localization\Country;
use App\Models\Company\CompanyInvitation;
use Modules\Settings\Notifications\CompanyInvitationNotification;

class OnboardingWizard extends SimpleWizard
{
    use WithFileUploads;

    public $company, $document_type, $document, $selfie, $documentPreview, $selfiePreview, $photo, $image_path, $default_img;

    public $type, $invoicing = 'rate', $name, $country, $street, $city, $state, $zip, $description, $floors = 0, $companyEmail, $companyPhone, $companyStreet, $companyCity, $companyState, $companyZip, $companyCountry;

    public $unitName, $unitFloor, $numberUnits = 1, $capacity = 1, $unitType, $unitSize = 0, $unitDesc, $unitPrice = 0, $prices = 1, $priceRate, $unitRate = 0;

    public $memberName, $memberEmail, $memberRole;
    public string $videoUrl = '';

    public array $propertyTypes = [], $countries = [], $selectedAmenity = [], $propertyFloors = [], $propertyUnits = [], $leaseTerms = [], $unitFeatures = [], $units = [], $unitTypes = [], $unitPrices = [], $teamMembers = [], $roles = [];

    public function mount(){
        $this->showButtons = false;
        $this->isOnboarding = true;
        $this->currentStep = Auth::user()->onboarding_step;

        $this->company = current_company();
        $this->image_path = current_company()->avatar;
        $this->companyCity = current_company()->city;
        $this->companyCountry = current_company()->country_id;

        $this->propertyTypes = toSelectOptions(PropertyType::isCompany(current_company()->id)->where('property_type_group', '!=', 'commercial')->get(), 'id', 'name');
        $this->countries = toSelectOptions(Country::all(), 'id', 'common_name');

        $unitTypes = [
            // Basic & Standard Rooms
            ['id' => 'single-room',          'label' => 'Single Room 🛏️'],
            ['id' => 'double-room',          'label' => 'Double Room 🛏️🛏️'],
            ['id' => 'twin-room',            'label' => 'Twin Room 🛏️🛏️'],
            ['id' => 'double-double-room',   'label' => 'Double-Double Room 🛏️🛏️'], // two double beds
            ['id' => 'queen-room',           'label' => 'Queen Room 👑🛏️'],
            ['id' => 'king-room',            'label' => 'King Room 👑🛏️'],
            ['id' => 'triple-room',          'label' => 'Triple Room 🛏️🛏️🛏️'],
            ['id' => 'quadruple-room',       'label' => 'Quadruple Room 🛏️🛏️🛏️🛏️'],
            ['id' => 'family-room',          'label' => 'Family Room 👨‍👩‍👧‍👦'],
            ['id' => 'bunk-room',            'label' => 'Bunk Room 🛏️🛏️'],
            ['id' => 'connecting-room',      'label' => 'Connecting Rooms 🔗'],
            ['id' => 'adjoining-room',       'label' => 'Adjoining Room 🚪'],

            // Premium & Luxury Rooms
            ['id' => 'standard-room',        'label' => 'Standard Room 🌟'],
            ['id' => 'deluxe-room',          'label' => 'Deluxe Room 🌟'],
            ['id' => 'superior-room',        'label' => 'Superior Room ✨'],
            ['id' => 'club-room',            'label' => 'Club Room 🎟️'],
            ['id' => 'executive-room',       'label' => 'Executive Room 💼'],
            ['id' => 'junior-suite',         'label' => 'Junior Suite 🏡'],
            ['id' => 'family-suite',         'label' => 'Family Suite 👨‍👩‍👧‍👦'],
            ['id' => 'bridal-suite',         'label' => 'Bridal Suite 💐'],
            ['id' => 'suite',                'label' => 'Suite 🏢'],
            ['id' => 'panoramic-suite',      'label' => 'Panoramic Suite 🌅'],
            ['id' => 'royal-suite',          'label' => 'Royal Suite 👑'],
            ['id' => 'presidential-suite',   'label' => 'Presidential Suite 🏆'],
            ['id' => 'penthouse',            'label' => 'Penthouse 🌆'],

            // Specialty & Themed Rooms
            ['id' => 'honeymoon-suite',      'label' => 'Honeymoon Suite 💕'],
            ['id' => 'wellness-room',        'label' => 'Wellness Room 🧘'],
            ['id' => 'accessible-room',      'label' => 'Accessible Room ♿'],
            ['id' => 'tatami-room',          'label' => 'Tatami Room 🎎'],
            ['id' => 'themed-room',          'label' => 'Themed Room 🎭'],
            ['id' => 'smart-room',           'label' => 'Smart Room 🤖'],
            ['id' => 'cave-suite',           'label' => 'Cave Suite 🪨'],
            ['id' => 'riad-room',            'label' => 'Riad Room 🕌'],

            // Apartment & Long-Stay Options (Hotel-style)
            ['id' => 'studio-room',          'label' => 'Studio Room 🏢'],
            ['id' => 'loft-room',            'label' => 'Loft Room 🏙️'],
            ['id' => 'duplex-room',          'label' => 'Duplex Room 🏠'],
            ['id' => 'efficiency-apartment', 'label' => 'Efficiency Apartment 🔄'],

            // Budget & Shared Accommodation
            ['id' => 'shared-dormitory',     'label' => 'Shared Dormitory 🏘️'],
            ['id' => 'mixed-dorm',           'label' => 'Mixed Dorm 🛌'],
            ['id' => 'female-dorm',          'label' => 'Female Dorm 🚺'],
            ['id' => 'male-dorm',            'label' => 'Male Dorm 🚹'],
            ['id' => 'private-room-shared-bath', 'label' => 'Private Room (Shared Bath) 🚿'],
            ['id' => 'ensuite-room',         'label' => 'Ensuite Room 🛁'],
            ['id' => 'capsule-room',         'label' => 'Capsule Room 📦'],
            ['id' => 'micro-room',           'label' => 'Micro Room 🚪'],
            ['id' => 'pod-dorm',             'label' => 'Pod Dorm 🧩'],

            // Alternative Lodging / Resort
            ['id' => 'bungalow',             'label' => 'Bungalow 🏖️'],
            ['id' => 'cottage',              'label' => 'Cottage 🏡'],
            ['id' => 'chalet',               'label' => 'Chalet 🏔️'],
            ['id' => 'cabin',                'label' => 'Cabin 🌲'],
            ['id' => 'treehouse',            'label' => 'Treehouse 🌳'],
            ['id' => 'yurt',                 'label' => 'Yurt 🏕️'],
            ['id' => 'glamping-tent',        'label' => 'Glamping Tent ⛺'],
            ['id' => 'safari-tent',          'label' => 'Safari Tent 🐘'],
            ['id' => 'overwater-bungalow',   'label' => 'Overwater Bungalow 🌊'],

            // Villas & Homes
            ['id' => 'one-bedroom-villa',    'label' => 'One-Bedroom Villa 🏠'],
            ['id' => 'two-bedroom-villa',    'label' => 'Two-Bedroom Villa 🏠🏠'],
            ['id' => 'three-bedroom-villa',  'label' => 'Three-Bedroom Villa 🏠🏠🏠'],
            ['id' => 'pool-villa',           'label' => 'Pool Villa 🏊'],
            ['id' => 'beach-villa',          'label' => 'Beach Villa 🏝️'],
            ['id' => 'garden-villa',         'label' => 'Garden Villa 🌿'],

            // Apartments (Residential / Serviced)
            ['id' => 'studio-apartment',     'label' => 'Studio Apartment 🏢'],
            ['id' => 'alcove-studio',        'label' => 'Alcove Studio 🧩'],
            ['id' => 'one-bedroom-apartment','label' => 'One-Bedroom Apartment 🛏️'],
            ['id' => 'two-bedroom-apartment','label' => 'Two-Bedroom Apartment 🏡'],
            ['id' => 'three-bedroom-apartment','label'=> 'Three-Bedroom Apartment 🏠'],
            ['id' => 'maisonette-apartment', 'label' => 'Maisonette Apartment 🪜'],
            ['id' => 'railroad-apartment',   'label' => 'Railroad Apartment 🚆'],
            ['id' => 'loft-apartment',       'label' => 'Loft Apartment 🏙️'],
            ['id' => 'garden-apartment',     'label' => 'Garden Apartment 🌿'],
            ['id' => 'basement-apartment',   'label' => 'Basement Apartment ⬇️'],
            ['id' => 'penthouse-apartment',  'label' => 'Penthouse Apartment 🌆'],
            ['id' => 'corporate-apartment',  'label' => 'Corporate Apartment 💼'],
            ['id' => 'luxury-apartment',     'label' => 'Luxury Apartment 🌟'],
            ['id' => 'smart-apartment',      'label' => 'Smart Apartment 🤖'],
            ['id' => 'serviced-apartment',   'label' => 'Serviced Apartment 🏡'], // kept one; removed duplicate

            // Townhouses & Multi-Story Living
            ['id' => 'duplex',               'label' => 'Duplex 🏠🏠'],
            ['id' => 'triplex',              'label' => 'Triplex 🏡🏡🏡'],
            ['id' => 'townhouse',            'label' => 'Townhouse 🏘️'],
        ];

        $this->unitTypes = toSelectOptions($unitTypes, 'id', 'label');

        $this->leaseTerms = toSelectOptions(LeaseTerm::isCompany(current_company()->id)->get(), 'id', 'name');

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

        $this->roles = toSelectOptions($roles, 'id', 'label');

    }

    protected $rules = [
        'document_type' => 'required|string|in:id_card,passport,driver_license',
        'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'selfie' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ];

    public function steps(){
        return [
            // Step::make(0, 'Identity Verification 🔒', true),
            Step::make(0, 'Add First Property 🏡', false),
            Step::make(1, 'Define Your Units 🏢', false),
            Step::make(2, 'Invite Team Members 👥', false),
            Step::make(3, 'Personalization (Logo, Currency, Timezone) 🎨', false),
            Step::make(4, 'Final Step - Dashboard Tour 🚀', false),
        ];
    }

    public function stepPages(){
        return [
            // StepPage::make('Identity Verification 🔒', '', 0)->component('app::wizard.step-page.special.onboarding.identity'),
            StepPage::make('Add First Property 🏡', '', 0)->component('app::wizard.step-page.special.onboarding.add-property'),
            StepPage::make('Define Your Units 🏢', '', 1)->component('app::wizard.step-page.special.onboarding.add-units'),
            StepPage::make('Invite Team Members 👥', '', 2)->component('app::wizard.step-page.special.onboarding.invite-members'),
            StepPage::make('Personalization (Logo, Currency, Timezone) 🎨 👥', '', 3)->component('app::wizard.step-page.special.onboarding.personalization'),
            StepPage::make('final', '', 4)->component('app::wizard.step-page.special.onboarding.final'),
            // StepPage::make('confirmation', '', 6),
        ];
    }

    // Verify Identity

    public function updatedSelfie()
    {
        $this->validateOnly('selfie');
        $this->selfiePreview = $this->selfie->temporaryUrl();
    }

    public function submitIdentity()
    {
        $this->validate([
            'document_type' => 'required|string|in:id-card,passport,driver-license',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'selfie' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if(Auth::user()->identity == 'pending'){
            return $this->goToNextStep();
        }

        $documentPath = $this->document->store('identity_documents', 'public');
        $selfiePath = $this->selfie ? $this->selfie->store('identity_selfies', 'public') : null;

        IdentityVerification::create([
            'user_id' => Auth::user()->id,
            'document_type' => $this->document_type,
            'document_path' => $documentPath,
            'selfie_path' => $selfiePath,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Identity verification submitted successfully.');
        $this->goToNextStep();
    }

    // Add the unit to the propertyUnits array
    public function addUnit()
    {
        // Validate the form data before adding
        $this->validate([
            'unitName' => 'required|string',
            'unitDesc' => 'nullable|string',
            'numberUnits' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'unitSize' => 'nullable|numeric|min:1',
        ]);

        // Check if company units limit have been reached

        $allowedLimit = current_company()->team->subscription('main')->features()->where('tag', 'units')->value('value');
        $currentCount = current_company()->units()->count(); // Dynamic model name

        if ($currentCount >= $allowedLimit) {
            $title = "Units limit has been reached";
            $message = "You have reached your $allowedLimit units limit. Upgrade your plan to add more.";
            return session()->flash('error', $message);
        }

        // Add the current unit data to the propertyUnits array
        $this->propertyUnits[] = [
            'unitName' => $this->unitName,
            'unitFloor' => $this->unitFloor,
            'unitDesc' => $this->unitDesc,
            'numberUnits' => $this->numberUnits,
            'price' => $this->unitPrice,
            'capacity' => $this->capacity,
            'unitSize' => $this->unitSize,
            'unitPrices' => $this->unitPrices,
            'unitFeatures' => $this->unitFeatures,
            'units' => $this->units,
        ];

        // Optionally, reset the form fields for the next entry
        $this->unitFeatures = [];
        $this->unitPrices = [];
        $this->prices = 1;
        $this->units = [];
        $this->capacity = 1;
        $this->numberUnits = 1;
        $this->reset(['unitName', 'unitDesc', 'numberUnits', 'capacity', 'unitSize', 'unitFeatures', 'unitPrice']);
    }

    public function removeUnit($index)
    {
        unset($this->propertyUnits[$index]);
        $this->propertyUnits = array_values($this->propertyUnits); // Reindex the array
    }

    public function updatedNumberUnits($value)
    {
        $unitCount = (int)$value;

        // Adjust the number of units in the array
        if ($unitCount > count($this->units)) {
            for ($i = count($this->units); $i < $unitCount; $i++) {
                $this->units[] = ['name' => '', 'floor' => ''];
            }
        } else {
            $this->units = array_slice($this->units, 0, $unitCount);
        }
    }

    public function removeTypeUnit($index)
    {
        if (isset($this->units[$index])) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
            $this->numberUnits = count($this->units); // Update the floors count
        }
    }

    // Save Property Units
    public function saveUnits($propertyId){

        foreach ($this->propertyUnits as $type) {
            // Create Unit Type
            $unitType = PropertyUnitType::create([
                'company_id' => current_company()->id,
                'property_id' => $propertyId,
                'name' => $type['unitName'],
                'description' => $type['unitDesc']?? null,
                'price' => $type['price'],
                'capacity' => $type['capacity'],
                'size' => $type['unitSize']?? null,
                // 'features' => json_encode($unit['unitFeatures']?? []),
            ]);

            if(count($type['unitPrices']) >= 1) {
                foreach ($type['unitPrices'] as $price) {
                    PropertyUnitTypePricing::updateOrCreate(
                        [
                            'company_id' => current_company()->id,
                            'property_id' => $propertyId,
                            'property_unit_type_id' => $unitType->id,
                            'lease_term_id' => $price['rate_type'],
                            'name' => $unitType->name . ' ' . lease_term($price['rate_type'])->name,
                        ],
                        [
                            'price' => $price['rate'] ?? 0,
                            'is_default' => $price['default'] ?? false,
                        ]
                    );
                }

                // Reset the component state
                $this->reset(['prices', 'unitPrices']);
            }

            // for($i = 0; $i < $unit['numberUnits']; $i++){
            foreach($type['units'] as $index => $unit) {
                    $floor = PropertyFloor::isCompany(current_company()->id)
                        ->where('name', $unit['floor'])
                        ->first() ?? null;

                    PropertyUnit::updateOrCreate(
                        [
                            'company_id' => current_company()->id,
                            'property_id' => $propertyId,
                            'floor_id' => $floor->id ?? null,
                            'property_unit_type_id' => $unitType->id,
                            'name' => $unit['name'],
                        ],
                        [
                            'capacity' => $type['capacity'],
                        ]
                    );

                    // Attach amenities to the property (éviter la duplication ici aussi)
                    if(count($type['unitFeatures']) >= 1) {
                        foreach($type['unitFeatures'] as $feature) {
                            PropertyFeature::firstOrCreate([
                                'company_id' => current_company()->id,
                                'property_unit_type_id' => $unitType->id,
                                'feature_id' => $feature,
                            ]);
                        }
                    }
            }

        }

        $this->propertyUnits = [];
    }


    public function updatedFloors($value)
    {
        $floorCount = (int)$value;

        // Adjust the number of floors in the array
        if ($floorCount > count($this->propertyFloors)) {
            for ($i = count($this->propertyFloors); $i < $floorCount; $i++) {
                $this->propertyFloors[] = ['name' => '', 'description' => ''];
            }
        } else {
            $this->propertyFloors = array_slice($this->propertyFloors, 0, $floorCount);
        }
    }

    public function removeFloor($index)
    {
        if (isset($this->propertyFloors[$index])) {
            unset($this->propertyFloors[$index]);
            $this->propertyFloors = array_values($this->propertyFloors);
            $this->floors = count($this->propertyFloors); // Update the floors count
        }
    }

    public function saveFloors($propertyId)
    {
        $this->validate([
            'propertyFloors.*.name' => 'required|string|max:255',
            'propertyFloors.*.description' => 'nullable|string|max:255',
        ]);

        foreach ($this->propertyFloors as $floor) {
            PropertyFloor::create([
                'company_id' => current_company()->id,
                'property_id' => $propertyId,
                'name' => $floor['name'],
                'description' => $floor['description'] ?? null,
            ]);
        }

        // Reset the component state
        $this->reset(['floors', 'propertyFloors']);
    }

    public function addPricing(){
        $this->prices++;
        // $priceCount = $this->prices++;

        // Adjust the number of floors in the array
        if ($this->prices > count($this->unitPrices)) {
            for ($i = count($this->unitPrices); $i < $this->prices; $i++) {
                $this->unitPrices[] = ['rate_type' => '', 'rate' => '', 'default' => false];
            }
        } else {
            $this->unitPrices = array_slice($this->unitPrices, 0, $this->prices);
        }

    }

    public function removePricing($index){
        if(isset($this->unitPrices[$index]) && count($this->unitPrices) > 1){
            unset($this->unitPrices[$index]);
            $this->unitPrices = array_values($this->unitPrices);
            $this->prices = count($this->unitPrices);
        }
    }

    public function savePricing($unit){
        // $this->validate([
        //     'unitPrices.*.rate_type' => 'required|integer',
        //     'unitPrices.*.rate' => 'required|integer',
        //     'unitPrices.*.default' => 'nullable',
        // ]);

        foreach ($this->unitPrices as $price) {
            PropertyUnitTypePricing::create([
                'company_id' => current_company()->id,
                'property_id' => $unit->property->id,
                'property_unit_type_id' => $unit->unitType->id,
                'lease_term_id' => $price['rate_type'],
                'name' => $unit->unitType->name.' '. lease_term($price['rate_type']),
                'price' => $price['rate'] ?? 0,
                'is_default' => $price['default'] ?? false,
            ]);
        }

        // Reset the component state
        $this->reset(['prices', 'unitPrices']);


    }

    public function addProperty(){
        $this->validate([
            'name' => 'required|string',
        ]);
        $this->goToNextStep();
    }

    public function submitProperty(){
        $this->validate([
            'name' => 'required|string|max:100',
        ]);
        // Create the property
        $property = Property::create([
            'company_id' => current_company()->id,
            'property_type_id' => $this->type,
            'name' => $this->name,
            'invoicing_type' => $this->invoicing,
            'country_id' => $this->country,
            'state_id' => $this->state,
            'city' => $this->city,
            'zip' => $this->zip,
            'address' => $this->street,
            'description' => $this->description,
            'status' => 'active',
        ]);

        // Attach floors to the property
        if(count($this->propertyFloors) >= 1){
            $this->saveFloors($property->id);
        }

        // Attach amenities to the property
        if(count($this->selectedAmenity) >= 1){
            foreach($this->selectedAmenity as $amenity){
                PropertyAmenity::create([
                    'company_id' => current_company()->id,
                    'property_id' => $property->id,
                    'amenity_id' => $amenity,
                ]);
            }
        }

        // Create Units & Unit Types
        if(count($this->propertyUnits) >= 1){
            $this->saveUnits($property->id);
        }

        $this->selectedAmenity = [];
        $this->reset(['name', 'type', 'description', 'country', 'invoicing', 'state', 'city', 'street', 'selectedAmenity']);

        // Flash success message
        session()->flash('success', __('Property has been saved successfully!'));
        $this->goToNextStep();


    }

    // Invite Team Members
    public function addMember()
    {
        $this->validate([
            // 'memberName' => 'required|string',
            'memberEmail' => 'required|email',
            'memberRole' => 'required|string',
        ]);

        $this->teamMembers[] = [
            'name' => $this->memberName,
            'email' => $this->memberEmail,
            'role' => $this->memberRole,
        ];

        // Reset input fields
        $this->reset(['memberName', 'memberEmail', 'memberRole']);
    }

    public function inviteMembers(){
        $this->validate([
            'teamMembers.*.email' => 'required|email',
            'teamMembers.*.role' => 'required|string',
        ]);

        // Create a new invitation record
        if(count($this->teamMembers) >= 1){
            foreach($this->teamMembers as $member){
                $this->sendInvitations($member);
            }
        }

        $this->reset(['teamMembers']);
        $this->goToNextStep();
    }

    public function sendInvitations($member){
        // Generate a unique invitation token
        $token = Str::random(32);

        $invitation = CompanyInvitation::create([
            'company_id' => current_company()->id,
            'property_id' => current_company()->properties()->first()->id ?? null,
            'email'     => $member['email'],
            'token' => $token,
            'role' => $member['role'],
            'expire_at' => now()->addDays(7),
        ]);
        $invitation->save();

        $invitation->notify(new CompanyInvitationNotification());
    }

    public function removeMember($index)
    {
        unset($this->teamMembers[$index]);
        $this->teamMembers = array_values($this->teamMembers); // Reindex the array
    }

    public function updatedPhoto(){
        // Validate the uploaded file
        $this->validate([
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
            $company = Company::find(current_company()->id);

            if(!$this->image_path){
                $this->image_path = $company->id . '_logo.png';

                // $this->photo->storeAs('avatars', $this->image_path, 'public');
                $company->update([
                    'avatar' => $this->image_path,
                ]);
            }

            $this->photo->storeAs('avatars', $this->image_path, 'public');


            // Send success message
            session()->flash('message', 'Logo updated successfully!');
    }

    public function submitCompany(){
        $company = Company::find(current_company()->id);

        $company->update([
            'phone' => $this->companyPhone,
            'email' => $this->companyEmail,
            'address' => $this->companyStreet,
            'city' => $this->companyCity,
            'country_id' => $this->companyCountry,
        ]);
        $company->save();

        $this->goToNextStep();
    }

    // Go to Dashboard
    public function goToDashboard()
    {
        $user = User::find(Auth::user()->id);
        $user->update([
            'onboarding_step' => 6,
            'onboarding_completed' => true,
            ]);

        $company = Company::find(current_company()->id);
        $company->update([
            'is_onboarded' => true,
        ]);

        return redirect()->route('dashboard');
    }

    public function increaseCapacity(){
        $this->capacity++;
    }

    public function decreaseCapacity(){
        if($this->capacity >= 1){
            $this->capacity--;
        }
    }
}
