<?php

namespace Modules\ChannelManager\Services\Booking;

use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\RevenueManager\Services\Pricing\RateService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingService
{
    protected RateService $rateService;

    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    /**
     * Calculate total booking price based on selected room and stay duration.
     *
     * @param PropertyUnit $room
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function calculatePrice(PropertyUnit $room, string $startDate, string $endDate): float
    {
        $nights = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

        return $this->rateService->getOptimalPricing($room->unitType->id, $nights);
    }

    /**
     * Calculate down payment based on system settings.
     *
     * @param float $totalAmount
     * @return float
     */
    public function calculateDownPayment(float $totalAmount): float
    {
        $downPaymentPercentage = settings()->down_payment ?? 30; // Default 30%
        return $totalAmount * ($downPaymentPercentage / 100);
    }

    /**
     * Check room availability for given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $people
     * @return mixed
     */
    public function getAvailableRooms(string $startDate, string $endDate, int $people)
    {
        return PropertyUnit::where('capacity', '>=', $people)
            ->where(function ($query) {
                $query->where('status', 'vacant')
                      ->orWhere('status', 'vacant-clean');
            })
            ->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
                $query->where('check_in', '<=', $endDate)
                      ->where('check_out', '>=', $startDate);
            })
            ->with(['unitType.prices' => fn($query) => $query->where('is_default', true)])
            ->get();
    }

    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function createBooking(array $data): Booking
    {
        $data['company_id'] = current_company()->id;
        $data['agent_id'] = Auth::id();
        $data['due_amount'] = max(0, $data['total_amount'] - $data['down_payment']);

        if ($data['down_payment'] > 0) {
            $data['status'] = 'confirmed';
            $data['payment_status'] = 'partial';
            $data['invoice_status'] = 'invoiced';
        } else {
            $data['status'] = 'pending';
            $data['payment_status'] = 'unpaid';
            $data['invoice_status'] = 'not_invoiced';
        }

        $booking = Booking::create($data);

        // Update room status
        if ($data['start_date'] == now()->toDateString() && $data['checked_in']) {
            $booking->update(['check_in_status' => 'checked_in']);
            PropertyUnit::find($data['property_unit_id'])->update(['status' => 'occupied']);
        }

        return $booking;
    }

    public function checkInBooking(Booking $booking)
    {
        // 1️⃣ Ensure the booking exists and is confirmed
        if (!$booking || $booking->status !== 'confirmed') {
            session()->flash('error', 'Invalid or unconfirmed booking.');
            return;
        }

        $downPayment = calculateDownPayment( $booking->total_amount, settings()->down_payment);
        // 2️⃣ Check if the guest has made the required payment
        if ($booking->paid_amount < $downPayment) {
            session()->flash('error', "Guest must complete a ".format_currency($downPayment)." payment before check-in.");
            return;
        }

        // 3️⃣ Ensure check-in date is today or earlier (no future check-ins)
        if ($booking->check_in > now()) {
            session()->flash('error', 'Check-in is not allowed before the booking date.');
            return;
        }

        // 4️⃣ Ensure room is in the right status before check-in
        if (!in_array($booking->unit->status, ['expected-arrival', 'reserved'])) {
            session()->flash('error', "The assigned unit " . $booking->unit->name . " is not available for check-in.");
            return;
        }

        // 5️⃣ Perform the check-in: Update room and booking status
        $booking->unit->update(['status' => 'occupied']); // Mark unit as occupied

        $booking->update([
            'check_in_status' => 'checked_in',
            'actual_check_in' => now(),
        ]);

        // 6️⃣ Success message
        session()->flash('success', 'Guest successfully checked in.');

        return $booking;
    }

    // Calculate early check-in charge (Example: Fixed 20% of daily rate)
    public function applyEarlyCheckInCharge(Booking $booking)
    {
        $charge = $booking->total_amount * 0.2; // 20% extra
        $booking->update([
            'extra_charge' => $charge,
            'total_amount' => $booking->total_amount + $charge,
        ]);
        return $charge;
    }
    
    public function checkOutBooking(Booking $booking)
    {
        // ✅ Ensure that the booking has not already been checked out
        if ($booking->status === 'completed') {
            session()->flash('warning', 'This booking has already been checked out.');
        }

        // ✅ Ensure payment is complete before check-out
        if ($booking->total_amount > $booking->amount_paid) {
            $outstandingBalance = $booking->total_amount - $booking->amount_paid;
            session()->flash('error', "Check-out denied! Outstanding balance of " . format_currency($outstandingBalance) . " must be cleared first.");
        }

        // ✅ Allow check-out on or before the check-out date
        if ($booking->check_out >= now()) {
            // Update the room's status (pending cleaning before availability)
            $booking->unit->update([
                'status' => 'vacant',
                'is_cleaned' => false,
            ]);

            // ✅ Update booking status
            $booking->update([
                'status' => 'completed',
                'check_out_status' => 'checked_out',
                'actual_check_out' => now(),
            ]);

            // ✅ Apply late check-out charge if applicable
            $lateCheckOutCharge = $this->applyLateCheckOutCharge($booking);

            // ✅ Handle post-check-out actions
            $this->handlePostCheckOutActions($booking);

            // Set success message
            session()->flash('success', 'Guest has successfully checked out.');

            // ✅ Add late check-out charge message if applicable
            if ($lateCheckOutCharge > 0) {
                session()->flash('info', 'Late check-out fee of ' . format_currency($lateCheckOutCharge) . ' applied.');
            }

        }

        // If check-out is not yet possible
        session()->flash('error', 'Check-out cannot be processed yet as the check-out date has not passed.');
    }

    public function applyLateCheckOutCharge(Booking $booking)
    {
        $scheduledCheckOut = Carbon::parse($booking->check_out);
        $actualCheckOut = now();
        
        if ($actualCheckOut->gt($scheduledCheckOut)) {
            $extraHours = $actualCheckOut->diffInHours($scheduledCheckOut);
            $extraCharge = $this->calculateLateCheckOutCharge($extraHours);

            $booking->update([
                'extra_charge' => $extraCharge,
                'total_amount' => $booking->total_amount + $extraCharge,
            ]);

            return $extraCharge;
        }

        return 0;
    }

    public function calculateLateCheckOutCharge($extraHours)
    {
        $ratePerHour = 10;
        return $ratePerHour * $extraHours;
    }
    

    public function handlePostCheckOutActions(Booking $booking)
    {
        // Example: Trigger a cleaning task for the room
        $this->triggerRoomCleaning($booking->unit);

        // Optionally, create an invoice or handle any post-check-out tasks here
        // $this->createInvoice($booking);
    }

    public function triggerRoomCleaning(PropertyUnit $unit)
    {
        // Logic for triggering cleaning task (e.g., status update, task creation, etc.)
        $unit->update(['is_cleaned' => false]);
    }

    public function updateBookingDate($bookingId, $start, $end)
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            session()->flash('error', 'Booking not found.');
            return;
        }

        $newCheckIn = Carbon::parse($start)->format('Y-m-d');
        $newCheckOut = Carbon::parse($end)->format('Y-m-d');

        // 1️⃣ Prevent past dates
        if ($newCheckIn < now()->format('Y-m-d')) {
            session()->flash('error', 'Cannot set a check-in date in the past.');
            return;
        }

        // 2️⃣ Ensure room is still available for the new dates
        if ($this->isRoomOccupied($booking->unit_id, $newCheckIn, $newCheckOut, $booking->id)) {
            session()->flash('error', 'The selected unit is unavailable for the new dates.');
            return;
        }

        // 3️⃣ Recalculate total amount based on new stay duration
        $oldTotal = $booking->total_amount;
        $newStayDuration = Carbon::parse($newCheckIn)->diffInDays(Carbon::parse($newCheckOut));
        $newTotal = $this->rateService->getOptimalPricing($booking->unit->unitType->id, $newStayDuration);

        // 4️⃣ Handle refund or additional charge
        $adjustment = $newTotal - $oldTotal;

        if ($adjustment > 0) {
            session()->flash('info', "An additional charge of KSh " . number_format($adjustment, 2) . " applies.");
        } elseif ($adjustment < 0) {
            session()->flash('info', "A refund of KSh " . number_format(abs($adjustment), 2) . " may be applied.");
        }

        // 5️⃣ Update booking dates
        $booking->update([
            'check_in' => $newCheckIn,
            'check_out' => $newCheckOut,
            'total_amount' => $newTotal,
        ]);

        session()->flash("success", "The booking #{$booking->reference} dates have been updated!");
    }

    /**
     * Checks if the unit is occupied for the given dates.
     */
    private function isRoomOccupied($unitId, $checkIn, $checkOut, $excludeBookingId = null)
    {
        return Booking::where('unit_id', $unitId)
            ->where('id', '!=', $excludeBookingId) // Exclude current booking
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut]);
            })
            ->exists();
    }

    /**
     * Calculates new price based on stay duration.
     */
    private function calculateNewPrice($unit, $checkIn, $checkOut)
    {
        $days = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
        return $unit->rate_per_night * $days;
    }

}
