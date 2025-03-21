<?php

namespace Modules\ChannelManager\Livewire\Form;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Modules\App\Livewire\Components\Form\Button\ActionBarButton;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\SimpleAvatarForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Traits\Form\Button\ActionBarButton as ActionBarButtonTrait;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\RevenueManager\Models\Accounting\Journal;

class BookingForm extends LightWeightForm
{
    use ActionBarButtonTrait;
    public Booking $booking;
    public $unit, $invoice, $reference, $guest, $check_in, $check_out, $room, $booking_details, $guests, $term, $unitPrice, $invoiceStatus, $paymentMethod;
    public $paidAmount = 0, $dueAmount = 0, $downPayment = 0;
    public array $guestOptions = [], $roomOptions = [], $paymentOptions = [];
    // public $startDate, $enDate;
    protected $bookingService;

    public function boot(BookingService $bookingService){
        $this->bookingService = $bookingService;
    }

    // Define validation rules
    protected $rules = [
        'guest' => 'nullable|integer|exists:users,id',
        'unit' => 'nullable|integer|exists:property_units,id',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate' => 'required|date|after:startDate',
        'guests' => 'integer',
        'status' => 'nullable|string',
    ];

    public function mount($booking = null){
        $this->reference = 'New Booking';
        if($booking){
            $this->blocked = true;
            $this->booking = $booking;
            $this->reference = $booking->reference;
            $this->unit = $booking->property_unit_id;
            $this->guest = $booking->guest_id;
            $this->startDate = $booking->check_in;
            $this->endDate = $booking->check_out;
            $this->room = $booking->property_unit_id;
            $this->invoice = $booking->invoice ?? 0;
            $this->booking_details = Booking::find($this->booking->id)->first();
            $this->guests = $booking->guests;
            $this->status = $booking->status;
            $this->paymentMethod = $booking->payment_method;
            $this->invoiceStatus = $booking->invoice_status;
            // $pricing = PropertyUnitType::find($this->type)->price;
            $this->roomPrice = $booking->unit_price;

            $this->calculatePrice();

        }
        $this->guestOptions = toSelectOptions(Guest::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->roomOptions = toSelectOptions(PropertyUnit::isCompany(current_company()->id)->get(), 'id', 'name');
        $payments = [
            ['id' => 'cash', 'label' => 'Cash'],
            ['id' => 'bank', 'label' => 'Bank'],
            ['id' => 'm-pesa', 'label' => 'M-Pesa'],
        ];
        $this->paymentOptions = toSelectOptions($payments, 'id', 'label');
    }
    // Action Bar Button
    public function actionBarButtons() : array
    {
        $type = $this->status;

        $buttons =  [
            // ActionBarButton::make('invoice', 'Créer une facture', 'storeQT()', 'sale_order'),
            ActionBarButton::make('send-email', __('Send by Email'), "", 'confirmed', true),
            ActionBarButton::make('confirm', __('Confirm'), "confirm", 'pending', $this->status == 'confirmed' || $this->status == 'completed'),
            ActionBarButton::make('invoice', __('Create Invoice'), "createInvoice", 'confirmed', $this->status !== 'confirmed' || $this->invoiceStatus == 'invoiced'),
            ActionBarButton::make('preview', __('Preview'), 'sale()', 'none', $this->status == null),
            ActionBarButton::make('lock', __('Lock'), 'lock()', "none", $this->blocked || $this->status == null),
            ActionBarButton::make('unlock', __('Unlock'), 'unlock()', 'blocked', !$this->blocked || $this->status == null),
            ActionBarButton::make('canceled', __('Cancel'), 'cancel', 'canceled', !$this->status || $this->status != 'confirmed'),
            // Add more buttons as needed
        ];

        // Define the custom order of button keys
        $customOrder = ['send-email', 'confirm', 'invoice', 'canceled']; // Adjust as needed

        // Change dynamicaly the display order depends on status
        return $this->sortActionButtons($buttons, $customOrder, $type);
    }

    public function statusBarButtons() : array
    {
        return [
            StatusBarButton::make('pending', __('Pending'), 'pending'),
            StatusBarButton::make('confirmed', __('Confirmed'), 'confirmed'),
            StatusBarButton::make('completed', __('Completed'), 'completed'),
            StatusBarButton::make('canceled', __('Canceled'), 'canceled'),
            // StatusBarButton::make('sale_order', __('translator::sales.form.sale.status.order'), 'sale_order')->component('button.status-bar.default-selected'),
            // Add more buttons as needed
        ];
    }

    public function capsules() : array
    {
        return [
            Capsule::make('invoice', __('Invoice'), __('Reservations made via this channel'), 'link', 'fa fa-file-invoice', route('bookings.invoices.show', ['invoice' => $this->invoice]), ['parent' => $this->invoice, 'amount' => ''])->component('app::form.capsule.depends'),
            Capsule::make('room', __('Room'), __('Unit linked to the reservation'), 'link', 'fa fa-home', route('properties.units.show', ['unit' => $this->unit ?? null]), ['parent' => $this->unit, 'amount' => ''])->component('app::form.capsule.depends'),
        ];
    }


    public function inputs(): array
    {
        return [
            Input::make('guests', 'Guest', 'select', 'guest', 'left', 'none', 'none', "", "", $this->guestOptions),
            Input::make('unit', 'Unit', 'select', 'unit', 'left', 'none', 'none', "", "", $this->roomOptions)->component('app::form.input.change-input'),
            Input::make('check-in', 'Check In', 'date', 'startDate', 'right', 'none', 'none', "", "")->component('app::form.input.change-input'),
            Input::make('check-out', 'Check Out', 'date', 'endDate', 'right', 'none', 'none', "", "")->component('app::form.input.change-input'),
            Input::make('guests', 'How Many Person', 'tel', 'guests', 'right', 'none', 'none', "", ""),
            // Input::make('down-payment', 'Minimum Deposit', 'tel', 'downPayment', 'right', 'none', 'none', "", ""),
            Input::make('payment-unit', 'Payment Method', 'select', 'paymentMethod', 'right', 'none', 'none', "", "", $this->paymentOptions),
        ];
    }

    public function unlock(){
        $this->blocked = false;
    }

    public function lock(){
        $this->blocked = true;
    }

    public function confirm(){
        $this->validate();
        if($this->booking){
            $this->status = 'confirmed';
            $this->booking->update(['status' => $this->status]);
        }
    }

    public function cancel(){
        $this->validate();
        if($this->booking){
            $this->bookingService->cancelBooking($this->booking);
        }
    }

    public function createInvoice(){
        $booking = $this->booking;

        $invoice = BookingInvoice::create([
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            // 'reference' => 'Booking Invoice',
            'guest_id' => $booking->guest_id,
            'date' => now(),
            // 'payment_term' => $booking->payment_term,
            'payment_status' => 'partial',
            'agent_id' => $booking->agent_id,
            'terms' => $booking->terms,
            'total_amount' => $booking->total_amount,
            'paid_amount' => $booking->paid_amount,
            'due_amount' => $booking->due_amount,
            'status' => 'draft',
            'to_checked' => false,
        ]);
        $invoice->save();

        if($booking->paid_amount >= 0){
            $journal = Journal::isCompany(current_company()->id)->isType($this->paymentMethod)->first();
            $payment = BookingPayment::create([
                'company_id' => $invoice->company_id,
                'booking_invoice_id' => $invoice->id,
                'journal_id' => $journal->id ?? null,
                'amount' => $invoice->paid_amount,
                'due_amount' => $invoice->due_amount,
                'date' => now(),
                'note' => 'Payment Received for Invoice #'. $invoice->reference,
                // 'reference' => $invoice->reference,
                'type' => 'debit',
                'payment_method' => $this->paymentMethod,
            ]);
            $payment->save();
        }

        $booking->update([
            'invoice_status' => 'invoiced'
        ]);
        return $this->redirect(route('bookings.invoices.show', ['invoice' => $invoice->id]), navigate: true);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if($this->unit){
            $unit = PropertyUnit::find($this->unit);
            $pricing = PropertyUnitType::find($unit->property_unit_type_id)->price;
            $this->roomPrice = $pricing->price;
        }

        if ($this->startDate && $this->endDate) {
            $this->calculatePrice();
        }

        // if ($early_check_in && !$this->isRoomAvailableForEarlyCheckIn()) {
        //     $this->addError('early_check_in', 'Early check-in is not available for this room.');
        // }
    }

    // public function updatedUnit($propertyName){
    // }

    public function calculatePrice()
    {
        $checkIn = Carbon::parse($this->startDate);
        $checkOut = Carbon::parse($this->endDate);
        $nights = $checkIn->diffInDays($checkOut);
        $this->nights = $nights;

        $this->totalAmount = $nights * $this->roomPrice;

        $this->calculateDownPayment();
    }

    public function calculateDownPayment()
    {
        $this->downPayment = $this->totalAmount * 0.3;
    }

    public function calculateTotal()
    {
        // Parse the dates
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Calculate the number of nights
        $nights = $start->diffInDays($end);
        $this->nights = $nights;

        // Calculate the total cost
        $this->totalAmount = $nights * $this->roomPrice;
    }


    #[On('create-booking')]
    public function createBooking(){
        $this->validate();
        if($this->downPayment >= 1){
            $this->dueAmount = $this->totalAmount - $this->downPayment;
        }

        $booking = Booking::create([
            'company_id' => current_company()->id,
            'property_unit_id' => $this->unit,
            'guest_id' => $this->guest,
            'check_in' => $this->startDate,
            'check_out' => $this->endDate,
            'unit_price' => $this->roomPrice,
            'paid_amount' => $this->downPayment,
            'due_amount' => $this->dueAmount,
            'total_amount' => $this->totalAmount,
        ]);
        $booking->save();
        return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
    }

    #[On('update-booking')]
    public function updateBooking(){
        $this->validate();
        $booking = Booking::find($this->booking->id);

        $booking->update([
            'property_unit_id' => $this->property_unit_id,
            'guest_id' => $this->guest,
            'check_in' => $this->startDate,
            'check_out' => $this->endDate,
            'total_amount' => $this->totalAmount,
        ]);
        $booking->save();
        return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
    }

    public function checkInBooking(Booking $booking) {
        // Only allow check-in if the check-in date is today or in the past
        if ($booking->check_in <= now() && $booking->check_in_status != 'checked_in') {
            // Update the room's status to 'occupied'
            $booking->unit->update([
                'status' => 'occupied'
            ]);

            // Update the booking's check-in status to 'checked_in'
            $booking->update([
                'check_in_status' => 'checked_in',
                'actual_check_in' => now(), // Store the actual check-in time
            ]);
        }
    }

    public function checkOutBooking(Booking $booking)
    {
        // Ensure that the booking has not already been checked-out
        if ($booking->check_out <= now() && $booking->status != 'completed') {
            // Update the room's status to 'available' after check-out
            $booking->unit->update([
                'status' => 'available'
            ]);

            // Update the booking's status to 'completed'
            $booking->update([
                'status' => 'completed', // Mark booking as completed
                'check_out_status' => 'checked_out', // Mark check-out status as 'checked_out'
                'actual_check_out' => now(), // Store the actual check-out time
            ]);

            // Optionally, handle any final actions (e.g., invoicing, cleaning, etc.)
            $this->handlePostCheckOutActions($booking);

            // Optionally, if you have a guest review system, you can prompt the guest for feedback
            // $this->promptForReview($booking);
            $msg = 'Guest has successfully checked out.';
            return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
        }

        // If check-out date is in the future (meaning it hasn't happened yet), handle accordingly
        $msg = 'Check-out cannot be processed yet as the check-out date has not passed.';
        return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
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
        $unit->update(['cleaning_status' => 'pending']);
    }

    public function applyLateCheckOutCharge(Booking $booking)
    {
        if ($booking->late_check_out) {
            // If late check-out is enabled, apply a charge
            $extraCharge = $this->calculateLateCheckOutCharge($booking);

            $booking->update([
                'extra_charge' => $extraCharge,
                'total_amount' => $booking->total_amount + $extraCharge, // Add the extra charge to the total amount
            ]);
        }
    }

    public function calculateLateCheckOutCharge(Booking $booking)
    {
        // Define the logic for calculating the late check-out charge
        // Example: charge an hourly rate for each extra hour
        $extraHours = $booking->extra_hours ?? 0;
        $ratePerHour = 10; // Example rate per hour (this could come from the pricing model)

        return $ratePerHour * $extraHours;
    }

}
