<?php

namespace Modules\ChannelManager\Services\Booking;

use Illuminate\Support\Str;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\RevenueManager\Services\Pricing\RateService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\App\Services\PaymentGateway\PaystackService;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\RevenueManager\Models\Accounting\Journal;

class BookingService
{
    protected RateService $rateService;
    protected PaystackService $paystackService;

    public function __construct(RateService $rateService, PaystackService $paystackService)
    {
        $this->rateService = $rateService;
        $this->paystackService = $paystackService;
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
            return redirect()->back();
        }

        // ✅ Ensure payment is complete before check-out
        if ($booking->total_amount !== $booking->paid_amount) {
            $outstandingBalance = $booking->total_amount - $booking->paid_amount;
            session()->flash('error', "Check-out denied! Outstanding balance of " . format_currency($outstandingBalance) . " must be cleared first.");
            return redirect()->back();
        }

        // ✅ Allow check-out on or before the check-out date
        // if ($booking->check_out >= now()) {
        if ($booking->check_out) {
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

        // ❌ Prevent updates for completed or canceled bookings
        if (in_array($booking->status, ['completed', 'canceled'])) {
            session()->flash('error', "Booking #{$booking->reference} cannot be modified because it is {$booking->status}.");
            return;
        }

        // 🚫 Prevent modifying check-in date if the guest has already checked in
        if ($booking->check_in_status == 'checked_in' && Carbon::parse($start)->format('Y-m-d') !== $booking->check_in) {
            session()->flash('error', "You cannot change the check-in date after the guest has checked in.");
            return;
        }

        // 1️⃣ Prevent past dates
        if ($newCheckIn < now()->format('Y-m-d')) {
            session()->flash('error', 'Cannot set a check-in date in the past.');
            return;
        }

        // 2️⃣ Ensure room is still available for the new dates
        if ($this->isRoomOccupied($booking->unit->id, $newCheckIn, $newCheckOut, $booking->id)) {
            session()->flash('error', "The selected {$booking->unit->name} is unavailable for the new dates.");
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
        return Booking::where('property_unit_id', $unitId)
            ->where('id', '!=', $excludeBookingId) // Exclude current booking
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut]);
            })
            ->exists();
    }

    public function cancelBooking(Booking $booking)
    {
        // 1️⃣ Ensure the booking is not already completed or canceled
        if (in_array($booking->status, ['canceled', 'completed'])) {
            session()->flash('error', 'This booking cannot be canceled as it is already ' . $booking->status . '.');
            return;
        }

        // Prevent cancellation after check-in
        if ($booking->check_in_status === 'checked_in') {
            session()->flash('error', 'This booking cannot be canceled because the guest has already checked in. Consider an early check-out instead.');
        }

        // 2️⃣ Apply refund policy if applicable
            // Calculate total paid amount
            $totalPaid = $booking->invoice->payments()->where('type', 'credit')->sum('amount');
        $refundAmount = settings()->has_refund_policy ? $this->applyRefundPolicy($booking) : 0;

        // Ensure refund does not exceed the total paid amount
        $refundAmount = min($refundAmount, $totalPaid);

        // 3️⃣ Cancel the booking & update refund amount
        $booking->update([
            'status' => 'canceled',
            'refund_amount' => $refundAmount,
        ]);

        // 4️⃣ Release the room (if it wasn't already reassigned)
        if (in_array($booking->unit->status, ['reserved', 'expected-arrival'])) {
            $booking->unit->update(['status' => 'vacant']);
        }

        // 5️⃣ Record refund as a "debit" in the booking_payments table
        if ($refundAmount > 0) {
            $this->processRefund($booking);
        }

        // Feedback messages
        $message = $refundAmount > 0
        ? "Booking #{$booking->reference} has been canceled. A refund of " . format_currency($refundAmount) . " will be processed."
        : "Booking #{$booking->reference} has been canceled. No refund is applicable.";

        session()->flash('success', $message);

    }

    /**
     * 🏨 Apply refund policy based on cancellation timing
     */
    private function applyRefundPolicy(Booking $booking)
    {
        $daysBeforeCheckIn = now()->diffInDays($booking->check_in);

        // 1️⃣ Full refund if canceled within the full_refund_days window
        if ($daysBeforeCheckIn >= settings()->full_refund_days) {
            return $booking->total_amount;
        }

        // 2️⃣ Partial refund if canceled within the partial_refund_days window
        if ($daysBeforeCheckIn < settings()->full_refund_days && $daysBeforeCheckIn >= settings()->partial_refund_days) {
            return $booking->total_amount * (settings()->partial_refund_percentage / 100);
        }

        // 3️⃣ Last-minute cancellation (no refund)
        return 0;
    }

    private function processRefund(Booking $booking)
    {
        // Call payment gateway or mobile money API to process refund
        // PaymentGateway::refund($booking->payment_reference, $booking->refund_amount);

        $booking->invoice->payments()->create([
            'amount'    => -$booking->refund_amount, // Negative value for a refund
            'type'      => 'debit',
            'method'    => $booking->payment_method, // Same method used for payment
            'reference' => 'REFUND/' . $booking->reference,
        ]);
    }

    public function cancelAndRebook(Booking $booking, $newCheckIn, $newCheckOut)
    {
        // 1️⃣ Apply refund policy
        $refundAmount = $this->applyRefundPolicy($booking);

        // 2️⃣ Cancel the booking
        $booking->update([
            'status' => 'cancelled',
            'refund_amount' => $refundAmount,
        ]);

        // 3️⃣ Create a new booking (if guest still wants to book)
        return redirect()->route('bookings.create', ['check_in' => $newCheckIn, 'check_out' => $newCheckOut]);
    }

    /**
     * Handle payment processing and recording.
     *
     * @param string $invoice Invoice to which the payment is linked
     * @param string $method Payment method (cash, bank, mobile_money, paystack)
     * @param float $amount Payment amount
     * @param int $bookingId Associated booking ID
     * @param array $extraData Additional data (e.g., transaction reference)
     */
    public function handlePayment(int $invoiceID, string $method, float $amount, int $bookingId)
    {
        $invoice = BookingInvoice::find($invoiceID);
        $booking = Booking::find($bookingId);

        $transactionId = Str::uuid(); // Generate unique transaction ID
        $extraData = [
            'invoiceId' => $invoice->id,
            'method' => $method,
            'bookingId' => $bookingId
        ];
        // If Paystack, process the payment
        if ($method === 'paystack') {
            return $this->paystackService->initializePayment($invoice->guest->name, $invoice->guest->email, $amount, $extraData);
            // $transactionId = $paystackResponse['data']['id'];
        }

        // Store payment record
        $journal = Journal::isCompany(current_company()->id)->isType($method)->first();
        $payment = BookingPayment::create([
            'company_id' => current_company()->id,
            'booking_invoice_id' => $invoice->id,
            'journal_id' => $journal->id,
            'payment_method' => $method,
            'amount' => $amount,
            'date' => now(),
            'note' => 'Payment Received for Invoice #'. $invoice->reference,
            'type' => 'credit',
        ]);
        $payment->save();

        // Process the after payment

        $due_amount = $invoice->due_amount - $payment->amount;

        if ($due_amount == $invoice->total_amount) {
            $payment_status = 'unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'partial';
        } else {
            $payment_status = 'paid';
        }
        $paidAmount = $invoice->paid_amount + $payment->amount;

        $invoice->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);

        $invoice->booking->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);

        return $payment;

    }


}
