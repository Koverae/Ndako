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

}
