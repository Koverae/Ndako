<?php

namespace Modules\ChannelManager\Services\Booking;

use Modules\Properties\Models\Property\PropertyUnitTypePricing;

class PricingService
{
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
                    ->orderBy('lease_terms.duration_in_days', 'desc')
                    ->get();

        $totalPrice = 0;

        foreach ($pricing as $price) {
            if ($stayDuration >= $price->duration_in_days) {
                $units = intdiv($stayDuration, $price->duration_in_days);
                $totalPrice += $units * ($price->discounted_price ?? $price->price);
                $stayDuration %= $price->duration_in_days;
            }
        }

        // Handle remaining days using nightly price
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
}