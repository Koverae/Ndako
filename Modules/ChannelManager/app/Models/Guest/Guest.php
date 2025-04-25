<?php

namespace Modules\ChannelManager\Models\Guest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Modules\App\Traits\Files\HasImportLogic;
use Modules\ChannelManager\Models\Booking\Booking;

class Guest extends Model
{
    use HasFactory, HasImportLogic;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'avatar',
        'name',
        'company_name',
        'language_id',
    
        // Address
        'street',
        'street2',
        'city',
        'state',
        'country_id',
        'zip',
    
        // Contact Info
        'identity_proof',
        'identity',
        'phone',
        'mobile',
        'email',
        'website',
        'tags',
    
        // Individual
        'job',
        'has_receipt_reminder',
        'days_before',
    
        // MISC
        'companyID',
        'reference',
        'note',
        'type',
        'status',
    ];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'guest_id', 'id');
    }

    
    public static function processImportPreviewRow(array $row, bool $forImport = false): array
    {

        Log::debug('Processing Row', [
            // 'unit_type_input' => $unitTypeName,
            // 'floor_input' => $floorName,
        ]);

        if ($forImport) {
            return [
                'company_id' => current_company()->id,
                'name' => $row['name'] ?? "",
                'description' => $row['description'] ?? "",
            ];
        }

        return [
            'Name' => $row['name'] ?? '',
            'Email' => $row['email'] ?? '',
            'Phone' => $row['phone'] ?? '',
            'Job' => $row['job'] ?? '',
            'Type' => $row['type'] ?? '',
            'Identity Proof' => $row['identity_proof'] ?? '',
            'Identity' => $row['identity'] ?? '',
            'City' => $row['city'] ?? '',
            'Country' => $row['country'] ?? '',
        ];
    }

}
