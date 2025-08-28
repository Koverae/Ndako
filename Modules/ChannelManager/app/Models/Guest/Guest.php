<?php

namespace Modules\ChannelManager\Models\Guest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\App\Traits\Files\HasImportLogic;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Settings\Models\Localization\Country;
use Modules\Settings\Models\Language\Language;

class Guest extends Model
{
    use HasFactory;
    use HasImportLogic;
    use SoftDeletes;

    /** -----------------------------------------------------------------
     *  Mass assignment & casting
     *  ----------------------------------------------------------------- */
    protected $fillable = [
        'company_id',
        'user_id',
        'avatar',
        'name',

        'company_name',
        'company_address',
        'monthly_income',
        'language_id',
        'birthday',
        'gender',

        // Address
        'street',
        'street2',
        'city',
        'state',
        'zip',
        'country_id',
        'nationality_id',

        // Contact / ID
        'identity_proof', // enum: id-card|passport|driving-license|resident-permit
        'identity',
        'phone',
        'mobile',
        'email',
        'website',
        'tags',
        'kin_name',
        'kin_email',
        'kin_address',
        'kin_phone',

        // Individual
        'job',
        'has_receipt_reminder',
        'days_before',

        // Misc
        'companyID',
        'reference',
        'note',
        'type',                 // enum: individual|company|agent
        'documents',            // json
        'verification_status',  // enum: pending|approved|rejected
        'status',               // bool
    ];

    protected $casts = [
        'user_id'              => 'int',
        'company_id'           => 'int',
        'language_id'          => 'int',
        'country_id'           => 'int',
        'nationality_id'       => 'int',
        'monthly_income'       => 'decimal:2',
        'birthday'             => 'date',
        'has_receipt_reminder' => 'bool',
        'days_before'          => 'int',
        'documents'            => 'array',
        'status'               => 'bool',
    ];

    /** Include handy computed props on arrays/json (optional) */
    protected $appends = [
        'formatted_location',
        'initials',
    ];

    /** -----------------------------------------------------------------
     *  Relationships
     *  ----------------------------------------------------------------- */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'guest_id');
    }

    public function orders()
    {
        return $this->hasMany(PosOrder::class, 'customer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /** -----------------------------------------------------------------
     *  Scopes
     *  ----------------------------------------------------------------- */
    public function scopeOfCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    /** Backwards compat (old name) */
    public function scopeIsCompany(Builder $query, int $companyId): Builder
    {
        return $this->scopeOfCompany($query, $companyId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;
        $term = trim($term);
        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('mobile', 'like', "%{$term}%")
              ->orWhere('identity', 'like', "%{$term}%")
              ->orWhere('company_name', 'like', "%{$term}%");
        });
    }

    /** -----------------------------------------------------------------
     *  Accessors / Mutators
     *  ----------------------------------------------------------------- */

    /** "City, Country" with graceful fallbacks */
    protected function formattedLocation(): Attribute
    {
        return Attribute::make(
            get: function () {
                $city = trim((string)$this->city);
                $country = $this->country?->common_name;
                return trim(collect([$city ?: null, $country ?: null])->filter()->join(', ')) ?: null;
            }
        );
    }

    /** Useful for avatar placeholders */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->name) return null;
                $parts = preg_split('/\s+/', trim($this->name));
                $first = mb_substr($parts[0] ?? '', 0, 1);
                $last  = mb_substr($parts[count($parts)-1] ?? '', 0, 1);
                return mb_strtoupper($first.$last);
            }
        );
    }

    /** Normalize email to lowercase; set to null if empty */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => $v ? mb_strtolower(trim($v)) : null
        );
    }

    /** Trim phones; keep null if empty */
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => $v ? trim($v) : null
        );
    }

    protected function mobile(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => $v ? trim($v) : null
        );
    }

    /** Ensure identity_proof uses DB enum values */
    protected function identityProof(): Attribute
    {
        return Attribute::make(
            set: function ($v) {
                $v = trim((string)$v);
                // Accept common synonyms, map to DB enum
                $map = [
                    'id-card'           => 'id-card',
                    'passport'          => 'passport',
                    'driver-license'    => 'driving-license', // normalize
                    'driving-license'   => 'driving-license',
                    'residence-permit'  => 'resident-permit', // normalize
                    'resident-permit'   => 'resident-permit',
                ];
                return $map[$v] ?? 'passport';
            }
        );
    }

    /** Normalize gender to allowed set */
    protected function gender(): Attribute
    {
        return Attribute::make(
            set: function ($v) {
                $v = mb_strtolower(trim((string)$v));
                return in_array($v, ['male','female','other'], true) ? $v : 'male';
            }
        );
    }

    /** -----------------------------------------------------------------
     *  Import helpers
     *  ----------------------------------------------------------------- */
    public static function processImportRow(array $data): array
    {
        // Country (residence)
        if (isset($data['country'])) {
            $country = Country::where('common_name', $data['country'])->first();
            $data['country_id'] = $country?->id;
            unset($data['country']);
        }

        // Nationality (citizenship)
        if (isset($data['nationality'])) {
            $nat = Country::where('common_name', $data['nationality'])->first();
            $data['nationality_id'] = $nat?->id;
            unset($data['nationality']);
        }

        // Address single-line field → street
        if (isset($data['address'])) {
            $data['street'] = $data['address'];
            unset($data['address']);
        }

        // Identity proof normalization (accept common variants)
        if (isset($data['identity_proof'])) {
            $map = [
                'id'                 => 'id-card',
                'id-card'            => 'id-card',
                'passport'           => 'passport',
                'driver-license'     => 'driving-license',
                'driving-license'    => 'driving-license',
                'residence-permit'   => 'resident-permit',
                'resident-permit'    => 'resident-permit',
            ];
            $key = mb_strtolower(trim((string)$data['identity_proof']));
            $data['identity_proof'] = $map[$key] ?? 'passport';
        }

        // Birthday coercion
        if (!empty($data['birthday'])) {
            $ts = strtotime($data['birthday']);
            $data['birthday'] = $ts ? date('Y-m-d', $ts) : null;
        }

        // Monthly income to decimal
        if (isset($data['monthly_income'])) {
            $data['monthly_income'] = is_numeric($data['monthly_income'])
                ? number_format((float)$data['monthly_income'], 2, '.', '')
                : 0;
        }

        // Email lowercasing
        if (isset($data['email'])) {
            $data['email'] = $data['email'] ? mb_strtolower(trim($data['email'])) : null;
        }

        return $data;
    }

    public static function processImportPreviewRow(array $row, bool $forImport = false): array
    {
        if ($forImport) {
            return [
                'company_id' => current_company()->id,
                'name'       => $row['name'] ?? '',
                'email'      => !empty($row['email']) ? mb_strtolower(trim($row['email'])) : null,
                'phone'      => $row['phone'] ?? null,
            ];
        }

        return [
            'Name'            => $row['name'] ?? '',
            'Email'           => $row['email'] ?? '',
            'Phone'           => $row['phone'] ?? '',
            'Job'             => $row['job'] ?? '',
            'Type'            => $row['type'] ?? '',
            'Identity Proof'  => $row['identity_proof'] ?? '',
            'Identity'        => $row['identity'] ?? '',
            'City'            => $row['city'] ?? '',
            'Country'         => $row['country'] ?? '',
            'Nationality'     => $row['nationality'] ?? '',
            'Birthday'        => $row['birthday'] ?? '',
            'Monthly Income'  => $row['monthly_income'] ?? '',
        ];
    }
}
