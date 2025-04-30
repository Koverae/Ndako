<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Properties\Database\Factories\Property/PropertyUnitFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Modules\App\Traits\Files\HasImportLogic;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\RevenueManager\Models\Expenses\Expense;

class PropertyUnit extends Model
{
    use HasFactory, SoftDeletes, HasImportLogic;

    protected $fillable = [
        'company_id',
        'property_id',
        'property_unit_type_id',
        'floor_id',
        'status_id',
        'name',
        'description',
        'images',
        'capacity',
        'default_setttings',
        'is_available',
        'is_cleaned',
        'status',
        'last_cleaned_at',
    ];

    // protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsProperty(Builder $query, $property_id)
    {
        return $query->where('property_id', $property_id);
    }

    public function scopeIsType(Builder $query, $type_id)
    {
        return $query->where('property_unit_type_id', $type_id);
    }

    public function property() {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function floor() {
        return $this->belongsTo(PropertyFloor::class, 'floor_id', 'id');
    }

    public function unitType() {
        return $this->belongsTo(PropertyUnitType::class, 'property_unit_type_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'property_unit_id', 'id');
    }

    public function expenses() {
        return $this->hasMany(Expense::class, 'property_unit_id', 'id');
    }

    // Import Logic

    public static function processImportRow(array $data): array
    {
        // Convert unit type name to ID
        if (isset($data['unit_type'])) {
            $unitType = PropertyUnitType::where('name', $data['unit_type'])->first();
            if (!$unitType) {
                Log::warning("Import: Unit type '{$data['unit_type']}' not found.");
            }
            $data['property_unit_type_id'] = $unitType?->id;

            // Remove the original 'unit_type' column to avoid mass assignment error
            unset($data['unit_type']);
        }

        // Do similar for 'property', 'floor', etc.
        if (isset($data['property'])) {
            $property = Property::where('name', $data['property'])->first();
            if (!$property) {
                Log::warning("Import: Property '{$data['property']}' not found.");
            }
            $data['property_id'] = $property?->id;
            unset($data['property']);
        }

        // Floor
        if (isset($data['floor'])) {
            $floor = PropertyFloor::where('name', $data['floor'])->first();
            if (!$floor) {
                Log::warning("Import: Floor '{$data['floor']}' not found.");
            }
            $data['floor_id'] = $floor?->id;
            unset($data['floor']);
        }

        return $data;
    }
    
    public static function processImportPreviewRow(array $row, bool $forImport = false): array
    {
        $propertyName = trim($row['property'] ?? '');
        $unitTypeName = trim($row['unit_type'] ?? '');
        $floorName = trim($row['floor'] ?? '');

        Log::debug('Processing Row', [
            'unit_type_input' => $unitTypeName,
            'floor_input' => $floorName,
        ]);

        $property = Property::whereRaw('LOWER(name) = ?', [strtolower($propertyName)])->first();
        $unitType = PropertyUnitType::whereRaw('LOWER(name) = ?', [strtolower($unitTypeName)])->first();
        $floor = PropertyFloor::whereRaw('LOWER(name) = ?', [strtolower($floorName)])->first();

        if ($forImport) {
            return [
                'company_id' => current_company()->id,
                'name' => $row['name'] ?? "",
                'description' => $row['description'] ?? "",
                'property_id' => optional($property)->id,
                'property_unit_type_id' => optional($unitType)->id,
                'floor_id' => optional($floor)->id,
            ];
        }

        return [
            'Name' => $row['name'] ?? '',
            'Description' => $row['description'] ?? '',
            'Property' => optional($property)->name ?? "❌ Not Found: $propertyName",
            'Unit Type' => optional($unitType)->name ?? "❌ Not Found: $unitTypeName",
            'Floor' => optional($floor)->name ?? "❌ Not Found: $floorName",
            // 'Status' => inverseSlug($row['status']),
        ];
    }

}
