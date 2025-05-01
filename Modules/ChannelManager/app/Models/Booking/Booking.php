<?php

namespace Modules\ChannelManager\Models\Booking;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\App\Traits\Files\HasImportLogic;
use Modules\ChannelManager\Models\Channel\Channel;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;

class Booking extends Model
{
    use HasFactory, HasImportLogic;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'channel_id',
        'property_unit_id',
        'guest_id',
        'agent_id',
        'reference',
        'guests',
        'note',
        'check_in',
        'check_out',
        'unit_price',
        'total_amount',
        'paid_amount',
        'due_amount',
        'refund_amount',
        'payment_type',
        'payment_status',
        'payment_method',
        'status',
        'source',
        'invoice_status',
        'early_check_in',
        'late_check_out',
        'extra_hours',
        'extra_charge',
        'actual_check_in',
        'actual_check_out',
        'check_in_status',
        'check_out_status',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Booking::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->created_at)->year;
            $month = Carbon::parse($model->created_at)->month;
            $model->reference = make_reference_with_month_id('ND/BK', $number, $year, $month);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsActive(Builder $query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeIsPending(Builder $query)
    {
        return $query->where('status', 'pending');
    }



    public function scopeIsUnit(Builder $query, $property_unit_id)
    {
        return $query->where('property_unit_id', $property_unit_id);
    }

    public function invoice() {
        return $this->hasOne(BookingInvoice::class);
    }

    public function unit() {
        return $this->belongsTo(PropertyUnit::class, 'property_unit_id', 'id');
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function payments() {
        return $this->hasMany(BookingPayment::class);
    }

    /**
     * Calculate the optimal pricing for a given booking duration.
     *
     * @param int $unitTypeId The ID of the property unit type.
     * @param int $stayDuration The total number of days the guest is booking.
     * @return float The total price for the booking.
     */
    public function getOptimalPricing($unitTypeId, $stayDuration)
    {
        // Fetch all applicable pricing sorted by longest duration first
        $pricing = PropertyUnitTypePricing::where('property_unit_type_id', $unitTypeId)
                    ->join('lease_terms', 'property_unit_type_pricings.lease_term_id', '=', 'lease_terms.id')
                    ->orderBy('lease_terms.duration_in_days', 'desc') // Start with longest durations
                    ->get();

        $totalPrice = 0; // Final price accumulator

        foreach ($pricing as $price) {
            if ($stayDuration >= $price->duration_in_days) {
                // Find how many full units of this duration fit
                $units = intdiv($stayDuration, $price->duration_in_days);
                // Multiply by price (use discounted price if available)
                $totalPrice += $units * ($price->discounted_price ?? $price->price);
                // Reduce the remaining stay duration
                $stayDuration %= $price->duration_in_days;
            }
        }

        // If there's any remaining days, fall back to the default price (nightly rate)
        if ($stayDuration > 0) {
            $nightlyPrice = PropertyUnitTypePricing::where('property_unit_type_id', $unitTypeId)
                            ->join('lease_terms', 'property_unit_type_pricings.lease_term_id', '=', 'lease_terms.id')
                            ->where('lease_terms.name', 'nightly')
                            ->first();

            if ($nightlyPrice) {
                $totalPrice += $stayDuration * ($nightlyPrice->discounted_price ?? $nightlyPrice->price);
            }
        }

        return $totalPrice;
    }



    // Import Logic

    public static function processImportRow(array $data): array
    {
        // Property
        if (isset($data['property'])) {

            $allProperties = Property::isCompany(current_company()->id)->get();
            $inputName = $data['property'];

            $property = $allProperties->sortByDesc(function ($property) use ($inputName) {
                similar_text(strtolower($property->name), strtolower($inputName), $percent);
                return $percent;
            })->first();

            if (!$property) {

            }
            $data['property_id'] = $property?->id;
            unset($data['property']);
        }else{
            session()->flash('error', 'The property is missing!');
        }

        // Room
        if (isset($data['room'])) {
            $room = PropertyUnit::where('name', $data['room'])->first();
            if (!$room) {

            }
            $data['property_unit_id'] = $room?->id;
            unset($data['room']);
        }

        // Guests
        if (isset($data['guest'])) {

            $allProperties = Property::isCompany(current_company()->id)->get();
            $inputName = $data['guest'];

            $guest = $allProperties->sortByDesc(function ($guest) use ($inputName) {
                similar_text(strtolower($guest->name), strtolower($inputName), $percent);
                return $percent;
            })->first();

            if (!$guest) {

            }
            $data['guest_id'] = $guest?->id;
            unset($data['guest']);
        }

        if (isset($data['no_guests'])) {
            if (!$room) {

            }
            $data['guests'] = $room?->id;
            unset($data['no_guests']);
        }

        // Date
        if (isset($data['check_in'])) {
            $checkIn = Carbon::parse($data['check_in'])->format('Y-m-d');
            $data['check_in'] = $checkIn;
            // Log::info('Date: '. $date);
        }

        if (isset($data['check_out'])) {
            $checkOut = Carbon::parse($data['check_out'])->format('Y-m-d');
            $data['check_out'] = $checkOut;
            // Log::info('Date: '. $date);
        }


        // Prices
        if (!isset($data['invoice_status'])) {
            $data['invoice_status'] = "invoiced";
            // Log::info('Date: '. $date);
        }

        if ($data['due_amount'] >= 1) {
            $data['payment_status'] = 'partial';
        }

        return $data;
    }

    public static function processImportPreviewRow(array $row, bool $forImport = false): array
    {
        $allProperties = Property::isCompany(current_company()->id)->get();
        $inputName = $row['property'];

        $property = $allProperties->sortByDesc(function ($property) use ($inputName) {
            similar_text(strtolower($property->name), strtolower($inputName), $percent);
            return $percent;
        })->first();

        $allGuests = Guest::isCompany(current_company()->id)->get();
        $inputName = $row['guest'];

        $guest = $allGuests->sortByDesc(function ($guest) use ($inputName) {
            similar_text(strtolower($guest->name), strtolower($inputName), $percent);
            return $percent;
        })->first();

        if(!$guest){
            session()->flash('error', 'We did not find this guest!');
        }

        $unit = PropertyUnit::where('name', $row['room'])->first();

        return [
            'Property' => $property->name ?? 'N/A',
            'Room' => $unit->name ?? 'N/A',
            'Source' => inverseSlug($row['source']) ?? 'N/A',
            'Reference' => $row['reference'] ?? 'N/A',
            'Guest' => $guest->name ?? 'N/A',
            'Check-in' => Carbon::parse($row['check_in'])->format('m/d/y') ?? '',
            'Check-out' => Carbon::parse($row['check_out'])->format('m/d/y') ?? '',
            'N° Guests' => $row['no_guests'] ?? 'N/A',
            'Status' => ucfirst($row['status']) ?? '',
            'Paid Amount' => format_currency($row['paid_amount']) ?? 'N/A',
            'Due Amount' => format_currency($row['due_amount']) ?? 'N/A',
            'Total Amount' => format_currency($row['total_amount']) ?? 'N/A',
        ];
    }

}
