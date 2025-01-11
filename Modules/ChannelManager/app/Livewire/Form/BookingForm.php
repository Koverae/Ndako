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
            ActionBarButton::make('send-email', __('Send by Email'), "", 'storable', true),
            ActionBarButton::make('confirm', __('Confirm'), "confirm", 'pending', $this->status == 'confirmed'),
            ActionBarButton::make('invoice', __('Create Invoice'), "createInvoice", 'confirmed', $this->status !== 'confirmed' || $this->invoiceStatus == 'invoiced'),
            ActionBarButton::make('preview', __('Preview'), 'sale()', 'none', $this->status == null),
            ActionBarButton::make('lock', __('Lock'), 'lock()', "none", $this->blocked || $this->status == null),
            ActionBarButton::make('unlock', __('Unlock'), 'unlock()', 'blocked', !$this->blocked || $this->status == null),
            // Add more buttons as needed
        ];

        // Define the custom order of button keys
        $customOrder = ['send-email', 'confirm', 'send', 'preview']; // Adjust as needed

        // Change dynamicaly the display order depends on status
        return $this->sortActionButtons($buttons, $customOrder, $type);
    }

    public function statusBarButtons() : array
    {
        return [
            StatusBarButton::make('pending', __('Pending'), 'pending'),
            StatusBarButton::make('confirmed', __('Confirmed'), 'confirmed'),
            // StatusBarButton::make('sale_order', __('translator::sales.form.sale.status.order'), 'sale_order')->component('button.status-bar.default-selected'),
            // StatusBarButton::make('canceled', __('translator::sales.form.sale.status.canceled'), 'canceled')->component('button.status-bar.canceled'),
            // Add more buttons as needed
        ];
    }

    public function capsules() : array
    {
        return [
            Capsule::make('invoice', __('Invoice'), __('Reservations made via this channel'), 'link', 'fa fa-file-invoice', Route::subdomainRoute('bookings.invoices.show', ['invoice' => $this->invoice]), ['parent' => $this->invoice, 'amount' => ''])->component('app::form.capsule.depends'),
            Capsule::make('room', __('Room'), __('Log of connections and actions'), 'link', 'fa fa-home', Route::subdomainRoute('properties.units.lists', ['unit' => $this->unit ?? null]), ['parent' => $this->unit, 'amount' => ''])->component('app::form.capsule.depends'),
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
            Input::make('down-payment', 'Minimum Deposit', 'tel', 'downPayment', 'right', 'none', 'none', "", ""),
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
        return $this->redirect(Route::subdomainRoute('bookings.invoices.show', ['invoice' => $invoice->id]), navigate: true);
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
        return $this->redirect(Route::subdomainRoute('bookings.show', ['booking' => $booking->id]), navigate: true);
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
        return $this->redirect(Route::subdomainRoute('bookings.show', ['booking' => $booking->id]), navigate: true);
    }

}
