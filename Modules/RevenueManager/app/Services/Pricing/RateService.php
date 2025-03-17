<?php

namespace Modules\RevenueManager\Services\Pricing;

use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;


class RateService{
    

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
            $remainingDays = $stayDuration;
            $nightlyPrice = null;

            foreach ($pricing as $price) {
                if ($price->name === 'nightly') {
                    $nightlyPrice = $price; // Save nightly price for later
                    continue;
                }

                if ($remainingDays >= $price->duration_in_days) {
                    $units = intdiv($remainingDays, $price->duration_in_days);
                    $totalPrice += $units * ($price->discounted_price ?? $price->price);
                    $remainingDays %= $price->duration_in_days;
                }
            }

            // Apply nightly price for remaining days
            if ($remainingDays > 0 && $nightlyPrice) {
                $totalPrice += $remainingDays * ($nightlyPrice->discounted_price ?? $nightlyPrice->price);
            }

            return $totalPrice;
    }

    /**
     * Get the default pricing rate for a given unit type.
     *
     * @param int $unitTypeId
     * @return PropertyUnitTypePricing|null
     */
    public function getDefaultRate(int $unitTypeId): ?PropertyUnitTypePricing
    {
        return PropertyUnitTypePricing::where('property_unit_type_id', $unitTypeId)
            ->where('is_default', true)
            // ->select(['id', 'price', 'discounted_price'])
            ->first();
    }
}