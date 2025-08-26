<?php

namespace Modules\Properties\Livewire\Wizard;

use Livewire\Attributes\On;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
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
use Modules\Settings\Models\Localization\Country;

class AddPropertyWizard extends SimpleWizard
{
    // Property
    public $type, $invoicing = 'rate', $name, $country, $street, $city, $state, $zip, $description, $floors = 0, $companyEmail, $companyPhone, $companyStreet, $companyCity, $companyState, $companyZip, $companyCountry;

    public $unitName, $unitFloor, $numberUnits = 1, $capacity = 1, $unitType, $unitSize = 0, $unitDesc, $unitPrice = 0, $prices = 1, $priceRate, $unitRate = 0;

    public array $propertyTypes = [], $countries = [], $selectedAmenity = [], $propertyFloors = [], $propertyUnits = [], $leaseTerms = [], $unitFeatures = [], $units = [], $unitTypes = [], $unitPrices = [];


    public function mount(){
        $this->showButtons = false;
        $this->propertyTypes = toSelectOptions(PropertyType::isCompany(current_company()->id)->where('property_type_group', '!=', 'commercial')->get(), 'id', 'name');
        $this->countries = toSelectOptions(Country::all(), 'id', 'common_name');
        $unitTypes = [
            // ─────────────────────────────────────────────────────────────
            // Basic & Standard Rooms
            // ─────────────────────────────────────────────────────────────
            ['id' => 'single-room',          'label' => 'Single Room 🛏️'],
            ['id' => 'double-room',          'label' => 'Double Room 🛏️🛏️'],
            ['id' => 'twin-room',            'label' => 'Twin Room 🛏️🛏️'],
            ['id' => 'double-double-room',   'label' => 'Double-Double Room 🛏️🛏️'],
            ['id' => 'queen-room',           'label' => 'Queen Room 👑🛏️'],
            ['id' => 'king-room',            'label' => 'King Room 👑🛏️'],
            ['id' => 'triple-room',          'label' => 'Triple Room 🛏️🛏️🛏️'],
            ['id' => 'quadruple-room',       'label' => 'Quadruple Room 🛏️🛏️🛏️🛏️'],
            ['id' => 'family-room',          'label' => 'Family Room 👨‍👩‍👧‍👦'],
            ['id' => 'bunk-room',            'label' => 'Bunk Room 🛏️🛏️'],
            ['id' => 'connecting-room',      'label' => 'Connecting Rooms 🔗'],
            ['id' => 'adjoining-room',       'label' => 'Adjoining Room 🚪'],

            // ─────────────────────────────────────────────────────────────
            // Premium & Luxury Rooms
            // ─────────────────────────────────────────────────────────────
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

            // ─────────────────────────────────────────────────────────────
            // Specialty & Themed Rooms
            // ─────────────────────────────────────────────────────────────
            ['id' => 'honeymoon-suite',      'label' => 'Honeymoon Suite 💕'],
            ['id' => 'wellness-room',        'label' => 'Wellness Room 🧘'],
            ['id' => 'accessible-room',      'label' => 'Accessible Room ♿'],
            ['id' => 'tatami-room',          'label' => 'Tatami Room 🎎'],
            ['id' => 'themed-room',          'label' => 'Themed Room 🎭'],
            ['id' => 'smart-room',           'label' => 'Smart Room 🤖'],
            ['id' => 'cave-suite',           'label' => 'Cave Suite 🪨'],
            ['id' => 'riad-room',            'label' => 'Riad Room 🕌'],

            // ─────────────────────────────────────────────────────────────
            // Apartment & Long-Stay Options (Hotel-style)
            // ─────────────────────────────────────────────────────────────
            ['id' => 'studio-room',          'label' => 'Studio Room 🏢'],
            ['id' => 'loft-room',            'label' => 'Loft Room 🏙️'],
            ['id' => 'duplex-room',          'label' => 'Duplex Room 🏠'],
            ['id' => 'efficiency-apartment', 'label' => 'Efficiency Apartment 🔄'],

            // ─────────────────────────────────────────────────────────────
            // Budget & Shared Accommodation
            // ─────────────────────────────────────────────────────────────
            ['id' => 'shared-dormitory',     'label' => 'Shared Dormitory 🏘️'],
            ['id' => 'mixed-dorm',           'label' => 'Mixed Dorm 🛌'],
            ['id' => 'female-dorm',          'label' => 'Female Dorm 🚺'],
            ['id' => 'male-dorm',            'label' => 'Male Dorm 🚹'],
            ['id' => 'private-room-shared-bath', 'label' => 'Private Room (Shared Bath) 🚿'],
            ['id' => 'ensuite-room',         'label' => 'Ensuite Room 🛁'],
            ['id' => 'capsule-room',         'label' => 'Capsule Room 📦'],
            ['id' => 'micro-room',           'label' => 'Micro Room 🚪'],
            ['id' => 'pod-dorm',             'label' => 'Pod Dorm 🧩'],

            // ─────────────────────────────────────────────────────────────
            // Alternative Lodging / Resort
            // ─────────────────────────────────────────────────────────────
            ['id' => 'bungalow',             'label' => 'Bungalow 🏖️'],
            ['id' => 'cottage',              'label' => 'Cottage 🏡'],
            ['id' => 'chalet',               'label' => 'Chalet 🏔️'],
            ['id' => 'cabin',                'label' => 'Cabin 🌲'],
            ['id' => 'treehouse',            'label' => 'Treehouse 🌳'],
            ['id' => 'yurt',                 'label' => 'Yurt 🏕️'],
            ['id' => 'glamping-tent',        'label' => 'Glamping Tent ⛺'],
            ['id' => 'safari-tent',          'label' => 'Safari Tent 🐘'],
            ['id' => 'overwater-bungalow',   'label' => 'Overwater Bungalow 🌊'],

            // ─────────────────────────────────────────────────────────────
            // Villas & Homes
            // ─────────────────────────────────────────────────────────────
            ['id' => 'one-bedroom-villa',    'label' => 'One-Bedroom Villa 🏠'],
            ['id' => 'two-bedroom-villa',    'label' => 'Two-Bedroom Villa 🏠🏠'],
            ['id' => 'three-bedroom-villa',  'label' => 'Three-Bedroom Villa 🏠🏠🏠'],
            ['id' => 'pool-villa',           'label' => 'Pool Villa 🏊'],
            ['id' => 'beach-villa',          'label' => 'Beach Villa 🏝️'],
            ['id' => 'garden-villa',         'label' => 'Garden Villa 🌿'],

            // ─────────────────────────────────────────────────────────────
            // Apartments (Residential / Serviced)
            // ─────────────────────────────────────────────────────────────
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
            ['id' => 'serviced-apartment',   'label' => 'Serviced Apartment 🏡'],

            // ─────────────────────────────────────────────────────────────
            // Meetings, Conferences & Event Spaces (MICE)
            // ─────────────────────────────────────────────────────────────
            ['id' => 'conference-center',    'label' => 'Conference Center 🏢'],
            ['id' => 'conference-hall',      'label' => 'Conference Hall 🎤'],
            ['id' => 'ballroom',             'label' => 'Ballroom 💃'],
            ['id' => 'banquet-hall',         'label' => 'Banquet Hall 🍽️'],
            ['id' => 'auditorium',           'label' => 'Auditorium 🎭'],
            ['id' => 'exhibition-hall',      'label' => 'Exhibition Hall 🖼️'],
            ['id' => 'multipurpose-hall',    'label' => 'Multipurpose Hall 🧩'],
            ['id' => 'meeting-room-small',   'label' => 'Meeting Room (Small) 🗣️'],
            ['id' => 'meeting-room-medium',  'label' => 'Meeting Room (Medium) 🗣️'],
            ['id' => 'meeting-room-large',   'label' => 'Meeting Room (Large) 🗣️'],
            ['id' => 'boardroom',            'label' => 'Boardroom 🧷'],
            ['id' => 'breakout-room',        'label' => 'Breakout Room 🔀'],
            ['id' => 'training-room',        'label' => 'Training Room 🧑‍🏫'],
            ['id' => 'seminar-room',         'label' => 'Seminar Room 🎓'],
            ['id' => 'classroom',            'label' => 'Classroom 🪑'],
            ['id' => 'press-room',           'label' => 'Press Room 📰'],
            ['id' => 'green-room',           'label' => 'Green Room 🎬'],
            ['id' => 'vip-lounge',           'label' => 'VIP Lounge ⭐'],
            ['id' => 'av-studio',            'label' => 'AV/Recording Studio 🎙️'],

            // ─────────────────────────────────────────────────────────────
            // Weddings & Social Events
            // ─────────────────────────────────────────────────────────────
            ['id' => 'wedding-venue',        'label' => 'Wedding Venue 💍'],
            ['id' => 'wedding-chapel',       'label' => 'Wedding Chapel ⛪'],
            ['id' => 'reception-hall',       'label' => 'Reception Hall 🥂'],
            ['id' => 'bridal-dressing-room', 'label' => 'Bridal Dressing Room 👰'],
            ['id' => 'grooms-lounge',        'label' => "Groom's Lounge 🤵"],
            ['id' => 'garden-pavilion',      'label' => 'Garden Pavilion 🌿'],
            ['id' => 'marquee-tent',         'label' => 'Marquee Tent ⛺'],
            ['id' => 'gazebo',               'label' => 'Gazebo 🏡'],
            ['id' => 'event-lawn',           'label' => 'Event Lawn 🌱'],
            ['id' => 'beachfront-venue',     'label' => 'Beachfront Venue 🏖️'],
            ['id' => 'rooftop-terrace',      'label' => 'Rooftop Terrace 🌇'],
            ['id' => 'pool-deck',            'label' => 'Pool Deck 🏊‍♂️'],

            // ─────────────────────────────────────────────────────────────
            // Workspaces & Offices
            // ─────────────────────────────────────────────────────────────
            ['id' => 'coworking-hot-desk',   'label' => 'Coworking Hot Desk 💻'],
            ['id' => 'coworking-dedicated-desk','label' => 'Dedicated Desk 💻'],
            ['id' => 'private-office',       'label' => 'Private Office 🗄️'],
            ['id' => 'project-room',         'label' => 'Project Room 🧩'],
            ['id' => 'computer-lab',         'label' => 'Computer Lab 🖥️'],

            // ─────────────────────────────────────────────────────────────
            // Wellness, Spa & Fitness
            // ─────────────────────────────────────────────────────────────
            ['id' => 'spa-treatment-room',   'label' => 'Spa Treatment Room 💆'],
            ['id' => 'couple-treatment-room','label' => 'Couple Treatment Room 💆‍♀️💆‍♂️'],
            ['id' => 'sauna',                'label' => 'Sauna ♨️'],
            ['id' => 'steam-room',           'label' => 'Steam Room 🌫️'],
            ['id' => 'hammam',               'label' => 'Hammam 🧖'],
            ['id' => 'yoga-studio',          'label' => 'Yoga Studio 🧘'],
            ['id' => 'fitness-studio',       'label' => 'Fitness Studio 🏋️'],
            ['id' => 'salt-room',            'label' => 'Salt Room 🧂'],

            // ─────────────────────────────────────────────────────────────
            // Food & Beverage Private Spaces
            // ─────────────────────────────────────────────────────────────
            ['id' => 'private-dining-room',  'label' => 'Private Dining Room 🍽️'],
            ['id' => 'chefs-table',          'label' => "Chef's Table 👨‍🍳"],
            ['id' => 'wine-cellar-room',     'label' => 'Wine Cellar/Tasting Room 🍷'],
            ['id' => 'lounge',               'label' => 'Lounge 🛋️'],
            ['id' => 'sky-bar',              'label' => 'Sky Bar 🌃'],
            ['id' => 'pool-bar',             'label' => 'Pool Bar 🍹'],
            ['id' => 'beach-club-cabana',    'label' => 'Beach Club Cabana 🏖️'],

            // ─────────────────────────────────────────────────────────────
            // Outdoor, Rooftop & Specialty Venues
            // ─────────────────────────────────────────────────────────────
            ['id' => 'courtyard',            'label' => 'Courtyard 🪴'],
            ['id' => 'terrace',              'label' => 'Terrace 🌤️'],
            ['id' => 'pavilion',             'label' => 'Pavilion 🏛️'],
            ['id' => 'amphitheater',         'label' => 'Amphitheater 🪗'],
            ['id' => 'firepit-area',         'label' => 'Firepit Area 🔥'],
            ['id' => 'bbq-area',             'label' => 'BBQ Area 🍖'],
            ['id' => 'game-room',            'label' => 'Game Room 🎮'],

            // ─────────────────────────────────────────────────────────────
            // Camping, Caravan & RV
            // ─────────────────────────────────────────────────────────────
            ['id' => 'campsite-pitch',       'label' => 'Campsite Pitch 🏕️'],
            ['id' => 'caravan-site',         'label' => 'Caravan Site 🚐'],
            ['id' => 'rv-site',              'label' => 'RV Site 🚌'],

            // ─────────────────────────────────────────────────────────────
            // Marina & Water
            // ─────────────────────────────────────────────────────────────
            ['id' => 'boat-slip',            'label' => 'Boat Slip / Berth 🚤'],
            ['id' => 'pontoon',              'label' => 'Pontoon 🛥️'],
            ['id' => 'kayak-rental',         'label' => 'Kayak Rental 🛶'],
            ['id' => 'sup-rental',           'label' => 'SUP Board Rental 🏄'],

            // ─────────────────────────────────────────────────────────────
            // Day Use & Passes
            // ─────────────────────────────────────────────────────────────
            ['id' => 'day-use-room',         'label' => 'Day-Use Room 🕘'],
            ['id' => 'pool-day-pass',        'label' => 'Pool Day Pass 🏊'],
            ['id' => 'spa-day-pass',         'label' => 'Spa Day Pass 💆'],

            // ─────────────────────────────────────────────────────────────
            // Sports & Leisure Facilities
            // ─────────────────────────────────────────────────────────────
            ['id' => 'tennis-court',         'label' => 'Tennis Court 🎾'],
            ['id' => 'padel-court',          'label' => 'Padel Court 🥎'],
            ['id' => 'squash-court',         'label' => 'Squash Court 🥍'],
            ['id' => 'basketball-court',     'label' => 'Basketball Court 🏀'],
            ['id' => 'mini-golf',            'label' => 'Mini Golf ⛳'],
            ['id' => 'golf-simulator',       'label' => 'Golf Simulator 🖥️⛳'],
            ['id' => 'bowling-lane',         'label' => 'Bowling Lane 🎳'],
            ['id' => 'billiard-room',        'label' => 'Billiard Room 🎱'],

            // ─────────────────────────────────────────────────────────────
            // Parking & Transport
            // ─────────────────────────────────────────────────────────────
            ['id' => 'parking-space',        'label' => 'Parking Space 🚗'],
            ['id' => 'ev-charging-bay',      'label' => 'EV Charging Bay ⚡🚘'],
        ];


        $this->unitTypes = toSelectOptions($unitTypes, 'id', 'label');

        $this->leaseTerms = toSelectOptions(LeaseTerm::isCompany(current_company()->id)->get(), 'id', 'name');
    }

    public function steps(){
        return [
            Step::make(0, 'Add First Property 🏡', false),
            Step::make(1, 'Define Your Units 🏢', false),
        ];
    }

    public function stepPages(){
        return [
            StepPage::make('Add First Property 🏡', '', 0)->component('app::wizard.step-page.special.property.add-property'),
            StepPage::make('Define Your Units 🏢', '', 1)->component('app::wizard.step-page.special.property.add-units'),
        ];
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

        // Reset form fields
        $this->reset();

        return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);


    }

    public function increaseCapacity(){
        $this->capacity++;
    }

    public function decreaseCapacity(){
        if($this->capacity >= 1){
            $this->capacity--;
        }
    }


    // public function confirm(){
    //     // Flash success message
    //     session()->flash('success', __('Property has been saved successfully!'));

    //     // Reset form fields
    //     $this->reset();

    //     return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);
    // }

}
