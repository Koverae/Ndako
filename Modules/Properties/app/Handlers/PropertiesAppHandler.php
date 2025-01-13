<?php
namespace Modules\Properties\Handlers;

use App\Models\Company\Company;
use Modules\App\Handlers\AppHandler;
use Modules\Properties\Models\Property\Amenity;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\PropertyType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Properties\Models\Property\Feature;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Properties\Models\Property\UnitStatus;
use Modules\Properties\Models\Property\Utility;

class PropertiesAppHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'properties';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        $this->configure($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    private function configure($companyId) : void
    {
        // Define property types data
        $propertyTypes = [
            [
                'company_id' => $companyId,
                'name' => 'Apartments/Flats',
                'description' => 'Multi-unit buildings with individual units.',
                'slug' => Str::slug('Apartment'),
                'icon' => 'fas fa-building',
                'is_active' => true,
                'property_type' => 'multi',
                'property_type_group' => 'residential',
                'attributes' => json_encode([
                    'floor' => 'integer',
                    'unit_number' => 'string',
                    'balcony' => 'boolean',
                ]),
                'default_settings' => json_encode([
                    'has_default_unit_status' => true,
                    'default_unit_status' => 'Available',
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Hotels',
                'description' => 'Accommodations for travellers often offering restaurants, meeting rooms and other guest services.',
                'slug' => Str::slug('Hotels'),
                'icon' => 'fas fa-hotel',
                'is_active' => true,
                'property_type' => 'multi',
                'property_type_group' => 'hospitality',
                'attributes' => json_encode([
                    'rooms' => 'integer',
                    'stars' => 'integer',
                    'amenities' => 'json',
                ]),
                'default_settings' => json_encode([
                    'has_default_unit_status' => true,
                    'default_unit_status' => 'Available',
                    'breakfast_included' => false,
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Serviced Apartments',
                'description' => 'Fully furnished apartments with hotel-like services.',
                'slug' => Str::slug('Serviced Apartments'),
                'icon' => 'serviced-apartment',
                'is_active' => true,
                'property_type' => 'multi',
                'property_type_group' => 'hospitality',
                'attributes' => json_encode([
                    'furnishings' => 'json',
                    'services' => 'json',
                ]),
                'default_settings' => json_encode([
                    'has_default_unit_status' => true,
                    'default_unit_status' => 'Available',
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Single-Family Homes',
                'description' => 'Standalone houses designed for one family.',
                'slug' => Str::slug('Single-Family Homes'),
                'icon' => 'home',
                'is_active' => true,
                'property_type' => 'single',
                'attributes' => json_encode([
                    'bedrooms' => 'integer',
                    'bathrooms' => 'integer',
                    'garage' => 'boolean',
                ]),
                'default_settings' => json_encode([
                    'has_default_unit_status' => true,
                    'default_unit_status' => 'Available',
                ]),
            ],
        ];

        // Insert property types into the database
        foreach ($propertyTypes as $type) {
            PropertyType::create($type);
        }

        // Seed Lease Terms
        $leaseTerms = [
            ['company_id' => $companyId, 'name' => 'Nightly', 'description' => 'Lease term for one night.', 'duration_in_days' => 1, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Monthly', 'description' => 'Lease term for one month.', 'duration_in_days' => 30, 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Quarterly', 'description' => 'Lease term for three months.', 'duration_in_days' => 90, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Annual', 'description' => 'Lease term for one year.', 'duration_in_days' => 365, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Bi-Annual', 'description' => 'Lease term for six months.', 'duration_in_days' => 180, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($leaseTerms as  $term) {
            LeaseTerm::create($term);
        }

        // Seed Unit Statuses
        $unitStatus = [
            ['company_id' => $companyId, 'name' => 'Occupied', 'description' => 'Unit is currently occupied.', 'is_housekeeping' => false, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Vacant', 'description' => 'Unit is currently vacant.', 'is_housekeeping' => false, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Under Maintenance', 'description' => 'Unit is under maintenance.', 'is_housekeeping' => true, 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($unitStatus as  $status) {
            UnitStatus::create($status);
        }

        // Seed Utilities
        $utilities = [
            ['company_id' => $companyId, 'name' => 'Electricity', 'description' => 'Electric power supply.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 10.50, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Water', 'description' => 'Water supply.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 5.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Internet', 'description' => 'High-speed internet.', 'is_included' => false, 'billing_cycle' => 'monthly', 'price_per_unit' => 30.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Gas', 'description' => 'Cooking gas supply.', 'is_included' => false, 'billing_cycle' => 'monthly', 'price_per_unit' => 15.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Cable TV', 'description' => 'Access to cable television channels.', 'is_included' => false, 'billing_cycle' => 'monthly', 'price_per_unit' => 20.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Security', 'description' => 'On-site security services.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 50.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Waste Management', 'description' => 'Waste collection and disposal services.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 10.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Backup Power', 'description' => 'Backup generator for power outages.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 25.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Pest Control', 'description' => 'Regular pest control services.', 'is_included' => false, 'billing_cycle' => 'quarterly', 'price_per_unit' => 15.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Fire Safety', 'description' => 'Fire safety systems and training.', 'is_included' => true, 'billing_cycle' => 'yearly', 'price_per_unit' => 100.00, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Laundry Services', 'description' => 'Laundry and dry cleaning facilities.', 'is_included' => false, 'billing_cycle' => 'weekly', 'price_per_unit' => 7.50, 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Landscaping', 'description' => 'Garden and lawn maintenance.', 'is_included' => true, 'billing_cycle' => 'monthly', 'price_per_unit' => 20.00, 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($utilities as  $utility) {
            Utility::create($utility);
        }

        // Seed Amenities
        $amenities = [
            ['company_id' => $companyId, 'name' => 'Swimming Pool', 'description' => 'Outdoor swimming pool.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Gym', 'description' => 'Fitness center.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Parking', 'description' => 'Ample parking space.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Conference Room', 'description' => 'Space for meetings and events.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Kids Play Area', 'description' => 'Designated area for children to play.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Spa', 'description' => 'Relaxation and wellness facilities.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Restaurant', 'description' => 'On-site dining options.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => '24/7 Reception', 'description' => 'Round-the-clock front desk services.', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $companyId, 'name' => 'Game Room', 'description' => 'Indoor recreational activities.', 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($amenities as  $amenity) {
            Amenity::create($amenity);
        }

        // Amenities

        $features = [
            ['name' => 'Balcony/Patio', 'category' => 'Living Spaces'],
            ['name' => 'Terrace', 'category' => 'Living Spaces'],
            ['name' => 'Loft', 'category' => 'Living Spaces'],
            ['name' => 'Private Garden', 'category' => 'Living Spaces'],
            ['name' => 'Walk-in Closet', 'category' => 'Living Spaces'],
            ['name' => 'Storage Room', 'category' => 'Living Spaces'],
            ['name' => 'Living Room', 'category' => 'Living Spaces'],
            ['name' => 'Fireplace', 'category' => 'Living Spaces'],
            ['name' => 'Ceiling Fans', 'category' => 'Living Spaces'],
            ['name' => 'Kitchenette', 'category' => 'Kitchen & Dining'],
            ['name' => 'Fully Equipped Kitchen', 'category' => 'Kitchen & Dining'],
            ['name' => 'Dishwasher', 'category' => 'Kitchen & Dining'],
            ['name' => 'Microwave', 'category' => 'Kitchen & Dining'],
            ['name' => 'Oven', 'category' => 'Kitchen & Dining'],
            ['name' => 'Refrigerator', 'category' => 'Kitchen & Dining'],
            ['name' => 'Pantry', 'category' => 'Kitchen & Dining'],
            ['name' => 'Breakfast Bar', 'category' => 'Kitchen & Dining'],
            ['name' => 'Dining Area', 'category' => 'Kitchen & Dining'],
            ['name' => 'Ensuite Bathroom', 'category' => 'Bathroom Features'],
            ['name' => 'Bathtub', 'category' => 'Bathroom Features'],
            ['name' => 'Shower', 'category' => 'Bathroom Features'],
            ['name' => 'Jacuzzi', 'category' => 'Bathroom Features'],
            ['name' => 'Double Sink Vanity', 'category' => 'Bathroom Features'],
            ['name' => 'Heated Towel Rack', 'category' => 'Bathroom Features'],
            ['name' => 'King/Queen Bed', 'category' => 'Bedroom Features'],
            ['name' => 'Twin Beds', 'category' => 'Bedroom Features'],
            ['name' => 'Bunk Beds', 'category' => 'Bedroom Features'],
            ['name' => 'Wardrobe', 'category' => 'Bedroom Features'],
            ['name' => 'Dressing Table', 'category' => 'Bedroom Features'],
            ['name' => 'Blackout Curtains', 'category' => 'Bedroom Features'],
            ['name' => 'Wi-Fi', 'category' => 'Connectivity & Entertainment'],
            ['name' => 'Cable TV', 'category' => 'Connectivity & Entertainment'],
            ['name' => 'Smart TV', 'category' => 'Connectivity & Entertainment'],
            ['name' => 'Home Theater System', 'category' => 'Connectivity & Entertainment'],
            ['name' => 'Gaming Console', 'category' => 'Connectivity & Entertainment'],
            ['name' => 'Air Conditioning', 'category' => 'Comfort & Climate'],
            ['name' => 'Central Heating', 'category' => 'Comfort & Climate'],
            ['name' => 'Underfloor Heating', 'category' => 'Comfort & Climate'],
            ['name' => 'Soundproofing', 'category' => 'Comfort & Climate'],
            ['name' => 'Insulated Windows', 'category' => 'Comfort & Climate'],
            ['name' => 'Electric Fans', 'category' => 'Comfort & Climate'],
            ['name' => 'Wheelchair Accessible', 'category' => 'Accessibility Features'],
            ['name' => 'Elevator Access', 'category' => 'Accessibility Features'],
            ['name' => 'Grab Bars', 'category' => 'Accessibility Features'],
            ['name' => 'Step-free Entry', 'category' => 'Accessibility Features'],
            ['name' => 'Safe', 'category' => 'Security Features'],
            ['name' => 'Security System', 'category' => 'Security Features'],
            ['name' => 'CCTV', 'category' => 'Security Features'],
            ['name' => 'Smoke Detectors', 'category' => 'Security Features'],
            ['name' => 'Carbon Monoxide Detectors', 'category' => 'Security Features'],
            ['name' => 'Fire Extinguisher', 'category' => 'Security Features'],
            ['name' => 'Swimming Pool Access', 'category' => 'Outdoor Features'],
            ['name' => 'Private Pool', 'category' => 'Outdoor Features'],
            ['name' => 'Barbecue Area', 'category' => 'Outdoor Features'],
            ['name' => 'Outdoor Seating', 'category' => 'Outdoor Features'],
            ['name' => 'Rooftop Access', 'category' => 'Outdoor Features'],
            ['name' => 'Children’s Play Area', 'category' => 'Outdoor Features'],
            ['name' => 'Office Desk', 'category' => 'Workspace Features'],
            ['name' => 'Ergonomic Chair', 'category' => 'Workspace Features'],
            ['name' => 'Study Area', 'category' => 'Workspace Features'],
            ['name' => 'Co-working Space', 'category' => 'Workspace Features'],
            ['name' => 'Washer/Dryer', 'category' => 'Utilities'],
            ['name' => 'Laundry Room', 'category' => 'Utilities'],
            ['name' => 'Electric Kettle', 'category' => 'Utilities'],
            ['name' => 'Water Dispenser', 'category' => 'Utilities'],
            ['name' => 'Solar Panels', 'category' => 'Utilities'],
            ['name' => 'Generator Backup', 'category' => 'Utilities'],
            ['name' => 'Sauna', 'category' => 'Luxury Features'],
            ['name' => 'Steam Room', 'category' => 'Luxury Features'],
            ['name' => 'Wine Cellar', 'category' => 'Luxury Features'],
            ['name' => 'Private Gym', 'category' => 'Luxury Features'],
            ['name' => 'Library', 'category' => 'Luxury Features'],
            ['name' => 'Pet-friendly', 'category' => 'Miscellaneous'],
            ['name' => 'Baby Cot Available', 'category' => 'Miscellaneous'],
            ['name' => 'High Chair', 'category' => 'Miscellaneous'],
            ['name' => 'Storage Locker', 'category' => 'Miscellaneous'],
            ['name' => 'Keyless Entry', 'category' => 'Miscellaneous'],
        ];
        foreach ($features as  $feature) {
            Feature::create(array_merge(['company_id' => $companyId], $feature));
        }

        // For test only
        // $this->buildProperty($companyId, PropertyType::isCompany($companyId)->first()->id);

    }

    public function buildProperty($company, $type){
        $properties = [
            [
                'company_id' => $company,
                'property_type_id' => 1, // Apartment
                'invoicing_type' => 'rental',
                'name' => 'Green Apartments',
                'description' => 'Spacious and modern apartments.',
                'country_id' => 1,
                'state_id' => 1,
                'city' => 'Nairobi',
                'zip' => '00100',
                'latitude' => '-1.286389',
                'longitude' => '36.817223',
                'address' => '123 Green Street',
                // 'amenities' => json_encode(['Swimming Pool', 'Gym', 'Wi-Fi']),
                'status' => 'active',
            ],
            [
                'company_id' => $company,
                'property_type_id' => 2, // Hotel
                'invoicing_type' => 'rate',
                'name' => 'Safari Hotel',
                'description' => 'Luxury hotel with excellent services.',
                'country_id' => 1,
                'state_id' => 1,
                'city' => 'Mombasa',
                'zip' => '80100',
                'latitude' => '-4.043477',
                'longitude' => '39.668206',
                'address' => '456 Beach Road',
                // 'amenities' => json_encode(['Swimming Pool', 'Gym', 'Wi-Fi']),
                'status' => 'active',
            ],
        ];

        foreach ($properties as  $property) {
            Property::create($property);
        }

        $floors = [
            [
                'company_id' => $company,
                'property_id' => 1, // Green Apartments
                'name' => 'Ground Floor',
                'description' => 'Main entrance and lobby.',
                'is_available' => true,
            ],
            [
                'company_id' => $company,
                'property_id' => 2, // Safari Hotel
                'name' => 'First Floor',
                'description' => 'Guest rooms and conference halls.',
                'is_available' => true,
            ],
        ];

        foreach ($floors as  $floor) {
            PropertyFloor::create($floor);
        }

        $unitTypes = [
            [
                'company_id' => $company,
                'property_id' => 1, // Green Apartments
                'pricing_id' => 1,
                'name' => 'Standard Apartment',
                'description' => 'One-bedroom apartment.',
                'is_available' => true,
            ],
            [
                'company_id' => $company,
                'property_id' => 2, // Safari Hotel
                'pricing_id' => 2,
                'name' => 'Standard Room',
                'description' => 'Standar room with a sea view.',
                'is_available' => true,
            ],
            [
                'company_id' => $company,
                'property_id' => 2, // Safari Hotel
                'pricing_id' => 2,
                'name' => 'Deluxe Room',
                'description' => 'Spacious room with a sea view.',
                'is_available' => true,
            ],
        ];

        foreach ($unitTypes as  $unitType) {
            PropertyUnitType::create($unitType);
        }

        $units = [
            [
                'company_id' => $company,
                'property_id' => 1, // Green Apartments
                'property_unit_type_id' => 1,
                'floor_id' => 1, // Ground Floor
                'status_id' => 1, // Occupied
                'name' => 'Unit 101',
                'description' => 'Facing the garden.',
                'attributes' => json_encode(['balcony' => true]),
                'is_available' => true,
                'is_cleaned' => true,
                'last_cleaned_at' => now(),
            ],
            [
                'company_id' => $company,
                'property_id' => 2, // Safari Hotel
                'property_unit_type_id' => 2,
                'floor_id' => 2, // First Floor
                'status_id' => 2, // Vacant
                'name' => 'Room 201',
                'description' => 'Overlooks the ocean.',
                'attributes' => json_encode(['mini-bar' => true]),
                'is_available' => true,
                'is_cleaned' => true,
                'last_cleaned_at' => now(),
            ],
            [
                'company_id' => $company,
                'property_id' => 2, // Safari Hotel
                'property_unit_type_id' => 3,
                'floor_id' => 2, // First Floor
                'status_id' => 2, // Vacant
                'name' => 'Room 202',
                'description' => 'Overlooks the ocean.',
                'attributes' => json_encode(['mini-bar' => true]),
                'is_available' => true,
                'is_cleaned' => true,
                'last_cleaned_at' => now(),
            ],
        ];

        foreach ($units as  $unit) {
            PropertyUnit::create($unit);
        }

        $unitTypePricings = [
            [
                'company_id' => $company,
                'property_unit_type_id' => 1, // Standard Apartment
                'property_id' => 1, // Green Apartments
                'lease_term_id' => 2, // (e.g., Monthly Lease Term)
                'name' => 'Standard Apartment Monthly Rent',
                'price' => 50000.00,
                'discounted_price' => 45000.00,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'is_per_night' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company,
                'property_unit_type_id' => 2, // Deluxe Room
                'property_id' => 2, // Safari Hotel
                'lease_term_id' => 1, // Not applicable for per-night pricing
                'name' => 'Deluxe Room Nightly Rate',
                'price' => 12000.00,
                'discounted_price' => 10000.00,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'is_per_night' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($unitTypePricings as  $pricing) {
            PropertyUnitTypePricing::create($pricing);
        }



    }
}

