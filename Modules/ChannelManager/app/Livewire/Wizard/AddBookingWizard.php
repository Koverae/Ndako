<?php

namespace Modules\ChannelManager\Livewire\Wizard;

use Livewire\Attributes\On;
use Modules\RevenueManager\Services\Pricing\RateService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\App\Events\NotificationEvent;
use Modules\App\Livewire\Components\Wizard\SimpleWizard;
use Modules\App\Livewire\Components\Wizard\Step;
use Modules\App\Livewire\Components\Wizard\StepPage;
use Modules\App\Models\Notification\Notification;
use Modules\App\Services\GuestCommunicationService;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\RevenueManager\Models\Accounting\Journal;

class AddBookingWizard extends SimpleWizard
{
    public $search = '', $guest, $selectedRoom, $startDate = '', $endDate = '', $guests, $status = 'pending', $paymentStatus = 'unpaid', $invoiceStatus = 'not_invoiced', $paymentMethod = 'cash';
    public $filterBy = 'capacity', $sortOrder = 'asc', $perPage = 5, $totalAmount = 0, $downPayment = 0, $downPaymentDue = 0, $dueAmount = 0, $nights = 0, $people = 1, $transactionId;
    public $availableRooms = [];
    public array $paymentOptions = [];
    public bool $checkedIn = true;
    protected $rateService, $bookingService;
    private GuestCommunicationService $guestCommunicationService;

    // Define validation rules
    protected $rules = [
        // 'guest' => 'nullable|integer|exists:users,id',
        // 'unit' => 'nullable|integer|exists:property_units,id',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate' => 'required|date|after:startDate',
        'people' => 'integer',
        'downPayment' => 'numeric|required',
        'status' => 'nullable|string',
        'checkedIn' => 'nullable|boolean',
    ];

    public function boot(RateService $rateService, BookingService $bookingService, GuestCommunicationService $guestCommunicationService){
        $this->rateService = $rateService;
        $this->bookingService = $bookingService;
        $this->guestCommunicationService = $guestCommunicationService;
    }

    public function mount($startDate = null, $endDate = null){

        $this->availableRooms = collect(); // Start with empty collection
        $this->startDate = $startDate ?? Carbon::now()->format('Y-m-d');
        $this->endDate = $endDate ?? Carbon::now()->addDay()->format('Y-m-d');

        $this->guests = Guest::isCompany(current_company()->id)->get();
        $this->downPaymentDue = $this->totalAmount * 0.3;
        // $this->selectedRoom = PropertyUnit::isCompany(current_company()->id)->first();
        // $this->guest = User::isCompany(current_company()->id)->first();
        $this->availableRooms = PropertyUnit::isCompany(current_company()->id)->get();
        $this->nights = dateDaysDifference($this->startDate, $this->endDate);

        $payments = [
            ['id' => 'cash', 'label' => 'Cash'],
            ['id' => 'bank', 'label' => 'Bank'],
            ['id' => 'm-pesa', 'label' => 'M-Pesa'],
        ];
        $this->paymentOptions = toSelectOptions($payments, 'id', 'label');
    }

    public function steps(){
        return [
            Step::make(0, 'Identity Card', true),
            Step::make(1, 'How Many People', false),
            Step::make(2, 'Pick A Room', false),
            Step::make(3, 'Confirmation', false),
        ];
    }

    public function stepPages(){
        return [
            StepPage::make('identity', 'Identity Card', 0)->component('app::wizard.step-page.special.booking.pick-guest'),
            StepPage::make('people', 'How Many People', 1)->component('app::wizard.step-page.special.booking.view-count'),
            StepPage::make('room', 'Pick A Room', 2)->component('app::wizard.step-page.special.booking.choose-room'),
            StepPage::make('confirmation', 'Confirmation', 3)->component('app::wizard.step-page.special.booking.confirmation'),
        ];
    }
    public function updatedSearch()
    {
        // Update guests based on search term
        $this->guests = Guest::isCompany(current_company()->id)
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->get();
    }


    public function loadMore()
    {
        $this->perPage += 5;
        $this->filterAvailableRooms();
    }

    public function updated($propertyName)
    {
        // $this->validateOnly($propertyName);

        if ($this->startDate && $this->endDate) {
            $this->validate([
                'startDate' => 'required|date|after_or_equal:today',
                'endDate' => 'required|date|after:startDate',
            ]);

            $this->calculatePrice();
        }

        if ($this->selectedRoom) {
            $this->calculatePrice();
        }

        if ($this->startDate && $this->endDate && $this->guests) {
            $this->filterAvailableRooms();
        }

        // if ($early_check_in && !$this->isRoomAvailableForEarlyCheckIn()) {
        //     $this->addError('early_check_in', 'Early check-in is not available for this room.');
        // }
    }


    public function calculatePrice()
    {
        if($this->selectedRoom){
            $checkIn = Carbon::parse($this->startDate);
            $checkOut = Carbon::parse($this->endDate);
            $nights = $checkIn->diffInDays($checkOut);
            $this->nights = $nights;

            $this->totalAmount = $this->rateService->getOptimalPricing($this->selectedRoom->unitType->id, $nights);

            $this->calculateDownPayment();
        }
    }

    public function calculateDownPayment()
    {
        if(settings()->down_payment){
            $this->downPaymentDue = $this->totalAmount * (settings()->down_payment / 100);
        }else{
            $this->downPaymentDue = 0;
        }
        // $this->downPaymentDue = $this->totalAmount * 0.3;
    }

    #[On('load-guests')]
    public function loadGuests(){
        $this->guests = Guest::isCompany(current_company()->id)->get();
    }

    public function pickGuest($guest){
        $this->guest = Guest::find($guest);
    }

    public function pickRoom($room){
        $this->selectedRoom = PropertyUnit::find($room);
        $this->calculatePrice();

        $this->goToNextStep();
    }

    public function filterAvailableRooms(){
        if (!$this->startDate || !$this->endDate || !$this->people) {
            return;
        }

        $this->availableRooms = PropertyUnit::where('capacity', '>=', $this->people)
            ->where('status', 'vacant') // Step 1: Get rooms that fit the number of people
                ->orWhere('status', 'vacant-clean')
                    ->whereDoesntHave('bookings', function ($query) { // Step 2: Exclude rooms that are already booked in the given date range
                        $query->where('check_in', '<=', $this->endDate)  // Check if check-in date is before or on the selected end date
                                ->where('check_out', '>=', $this->startDate);  // Check if check-out date is after or on the selected start date

                    })
                    ->with([
                        'unitType.prices' => fn($query) => $query->where('is_default', true), // Step 3: Eager load the default pricing for the unit type
                    ])
                    ->get() // Step 4: Fetch all results from the database
                        ->sortBy(fn($room) => match ($this->filterBy) { // Step 5: Sort the results based on user selection
                            'price'    => $room->unitType?->prices->first()?->price ?? 0, // Sort by price if available, otherwise default to 0
                            'capacity' => $room->capacity, // Sort by capacity if selected
                            default    => 0, // Default sorting (if no valid filter is provided)
                        }, SORT_REGULAR, $this->sortOrder === 'desc') // Step 6: Apply sorting order (ascending or descending)
                        ->values() // Step 7: Reset array keys (in case filtering removed some items)
                        ->take($this->perPage);
    }

    public function createBooking(){
        $this->validate();

        // Ensure dueAmount does not exceed totalAmount
        if ($this->downPayment > $this->totalAmount) {
            session()->flash('error', 'The paid amount exceeds the total amount for this booking.');
            return;
        }

        $this->dueAmount = $this->totalAmount - $this->downPayment;

        if ($this->downPayment >= 1) {
            $this->status = 'confirmed';
            $this->paymentStatus = 'partial';
            $this->invoiceStatus = 'invoiced';
        }

        $booking = Booking::create([
            'company_id' => current_company()->id,
            'property_unit_id' => $this->selectedRoom->id,
            'guest_id' => $this->guest->id,
            'agent_id' => Auth::user()->id,
            'guests' => $this->people,
            'check_in' => $this->startDate,
            'check_out' => $this->endDate,
            'unit_price' => $this->rateService->getDefaultRate($this->selectedRoom->unitType->id)->price,
            'paid_amount' => $this->downPayment,
            'due_amount' => $this->dueAmount,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'payment_status' => $this->paymentStatus,
            'invoice_status' => $this->invoiceStatus,
            // Add the check-in and check-out status fields
            'check_in_status' => 'pending', // Check if check-in is today
            'check_out_status' => 'pending', // Initial status
        ]);
        $booking->save();

        // $this->selectedRoom->update([
        //     'status' => 'occupied'
        // ]);
        if($booking->status == 'confirmed'){
            $this->dispatch('reservation-confirmed', booking: $booking);
        }

        // Check if the booking is for today or a future date
        if ($this->startDate == now()->toDateString()) {
            // If check-in is today, mark the room as occupied immediately
            if($this->checkedIn == true){
                $booking->update([
                    'check_in_status' => 'checked_in'
                ]);
            }
            $this->selectedRoom->update([
                'status' => 'occupied'
            ]);
        } else {
            // If check-in is in the future, mark the room as reserved
            $this->selectedRoom->update([
                'status' => 'expected-arrival'
                // 'status' => 'reserved'
            ]);
        }

        if($booking->paid_amount > 0){
            $this->createInvoice($booking);
        }

        session()->flash('success', __('Booking confirmed! Your reservation has been successfully added.'));

        // Send Notification
        $notification = Notification::create([
            'user_id' => Auth::user()->id,
            'company_id' => $booking->company_id,
            'type' => 'booking.created',
            'data' => [
                'message' => "New booking #{$booking->reference} for {$booking->guest->name}",
                'booking_id' => $booking->id,
            ],
        ]);

        event(new NotificationEvent($notification));

        // Send Email
        $this->sendBookingConfirmationEmail($booking);

        return $this->redirect(route('bookings.lists'), navigate: true);
        // return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
        // return $this->redirect(route('dashboard', ['dash' => 'home']), navigate: true);

    }

    public function createInvoice($booking){

        $invoice = BookingInvoice::create([
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            'guest_id' => $booking->guest_id,
            'date' => now(),
            'due_date' => $booking->check_out,
            'payment_status' => $booking->payment_status,
            'agent_id' => Auth::user()->id,
            'terms' => $booking->terms,
            'total_amount' => $booking->total_amount,
            'paid_amount' => $booking->paid_amount,
            'due_amount' => $booking->due_amount,
            'status' => 'posted',
            'to_checked' => false,
        ]);
        $invoice->save();

        if($booking->paid_amount >= 0){
            $journal = Journal::isCompany(current_company()->id)->isType($this->paymentMethod)->first();
            $payment = BookingPayment::create([
                'company_id' => $invoice->company_id,
                'booking_invoice_id' => $invoice->id,
                'journal_id' => $journal->id ?? null,
                'transaction_id' => $this->transactionId,
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
    }

    // Send Email
    public function sendBookingConfirmationEmail($booking){

        $model = [
            'guest_id' => (int) $booking->guest_id, // Ensure integer
            'booking_id' => (int) $booking->id, // Ensure integer
        ];

        $subjectReplace = [
            $booking->reference,
            current_property()->name ?? 'Hotel',
        ];

        $contentReplace = [
            $booking->guest->name ?? 'Arden BOUET',
            current_property()->name,
            // format_currency($booking->amount ?? 0),
            Carbon::parse($booking->check_in)->format('d M Y'),
            Carbon::parse($booking->check_out)->format('d M Y'),
            format_currency($booking->total_amount ?? 0),
            current_company()->name
        ];
        $data = [
            'total_amount' => format_currency($booking->amount ?? 0),
            'reference' => $booking->reference,
            'booking_reference' => $booking->reference,
            'date' => $booking->date,
            'check_in' => $booking->check_in,
            'check_out' => $booking->check_out,
            'room_type' => $booking->unit->unitType->name,
            'guest_count' => $booking->unit->unitType->capacity,
            // 'company_phone' => '+254 123 456 789',
        ];

        // Send Payment Receipt Email
        $this->guestCommunicationService->initiateTemplate(1, $model, $subjectReplace, $contentReplace, $data);
    }

}
