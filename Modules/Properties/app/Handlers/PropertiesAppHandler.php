<?php
namespace Modules\Properties\Handlers;

use App\Models\Company\Company;
use Modules\App\Handlers\AppHandler;
use Modules\Properties\Models\Property\Amenity;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\PropertyType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Properties\Models\Property\Property;
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
                'name' => 'Single-Family Homes',
                'description' => 'Standalone houses designed for one family.',
                'slug' => Str::slug('Single-Family Homes'),
                'icon' => 'home',
                'is_active' => true,
                'property_type_group' => 'residential',
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
            [
                'company_id' => $companyId,
                'name' => 'Apartments/Flats',
                'description' => 'Multi-unit buildings with individual units.',
                'slug' => Str::slug('Apartments/Flats'),
                'icon' => 'apartment',
                'is_active' => true,
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
                'description' => 'Temporary accommodations for travelers.',
                'slug' => Str::slug('Hotels'),
                'icon' => 'hotel',
                'is_active' => true,
                'property_type_group' => 'hospitality',
                'attributes' => json_encode([
                    'rooms' => 'integer',
                    'stars' => 'integer',
                    'amenities' => 'json',
                ]),
                'default_settings' => json_encode([
                    'has_default_unit_status' => true,
                    'default_unit_status' => 'Available',
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Serviced Apartments',
                'description' => 'Fully furnished apartments with hotel-like services.',
                'slug' => Str::slug('Serviced Apartments'),
                'icon' => 'serviced-apartment',
                'is_active' => true,
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

        // For test only
        $this->buildProperty($companyId, PropertyType::isCompany($companyId)->first()->id);

    }

    public function buildProperty($company, $type){
        Property::create([
            'company_id' => $company,
            'property_type_id' => $type, // Replace with a valid ID
            'name' => 'Sunset Residences',
            'description' => 'A luxury residential property with modern amenities.',
            'country_id' => 1, // Replace with a valid Country ID
            'state_id' => 1, // Replace with a valid State ID
            'city_id' => 1, // Replace with a valid City ID
            'zip' => '12345',
            'latitude' => '1.2921',
            'longitude' => '36.8219',
            'address' => '123 Sunset Blvd',
            'amenities' => json_encode(['Swimming Pool', 'Gym', 'Wi-Fi']),
            'status' => 'active'

        ]);
    }
}