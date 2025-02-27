<?php

namespace Modules\ChannelManager\Livewire\Wizard;

use Livewire\Attributes\On;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Wizard\SimpleWizard;
use Modules\App\Livewire\Components\Wizard\Step;
use Modules\App\Livewire\Components\Wizard\StepPage;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\RevenueManager\Models\Accounting\Journal;

class AddBookingWizard extends SimpleWizard
{
    public $search = '', $guest, $selectedRoom, $startDate = '', $endDate = '', $guests, $status = 'pending', $paymentStatus = 'unpaid', $invoiceStatus = 'not_invoiced', $paymentMethod = 'cash';
    public $filterBy = 'price', $sortOrder = 'asc', $totalAmount = 0, $downPayment = 0, $downPaymentDue = 0, $dueAmount = 0, $nights = 0, $people = 1;
    public $availableRooms = [];
    public array $paymentOptions = [];
    public bool $checkedIn = true;

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

    public function mount(){
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDay()->format('Y-m-d');
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

            $this->totalAmount = $nights * $this->selectedRoom->unitType->price;

            $this->calculateDownPayment();
        }
    }

    public function calculateDownPayment()
    {
        $this->downPaymentDue = $this->totalAmount * 0.3;
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

        // Step 1: Fetch all rooms that fit the number of people
        $rooms = PropertyUnit::where('capacity', '>=', $this->people)
            // ->where('status', 'vacant')
            // ->when($this->filterBy, function ($query) {
            //     $query->orderBy($this->filterBy, $this->sortOrder);
            // })
            ->with(['unitType.price']) // Eager load related price table
            ->get()
            ->sortBy(function ($room) {
                if ($this->filterBy === 'price') {
                    return $room->propertyType->price->price ?? 0; // Handle missing price
                } elseif ($this->filterBy === 'capacity') {
                    return $room->capacity;
                }
                return 0;
            }, SORT_REGULAR, $this->sortOrder === 'desc'); // Handle sorting order

            // Step 2: Filter rooms that are not booked within the provided date range
            $this->availableRooms = $rooms->filter(function ($room) {
                $isAvailable = !Booking::where('property_unit_id', $room->id)
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('check_in', '<=', $this->endDate)
                                  ->where('check_out', '>=', $this->startDate);
                        });
                    })
                    ->exists();

                return $isAvailable;
            })->values();
    }

    public function createBooking(){
        $this->validate();

        // Ensure dueAmount does not exceed totalAmount
        if ($this->downPayment > $this->totalAmount) {
            session()->flash('error', 'The paid amount exceeds the total amount for this booking.');
            return;
        }

        if($this->downPayment >= 1){
            $this->dueAmount = $this->totalAmount - $this->downPayment;
            $this->status = 'confirmed';
            $this->paymentStatus = 'partial';
            $this->invoiceStatus = 'invoiced';
        }

        $booking = Booking::create([
            'company_id' => current_company()->id,
            'property_unit_id' => $this->selectedRoom->id,
            'guest_id' => $this->guest->id,
            'agent_id' => auth()->user()->id,
            'guests' => $this->people,
            'check_in' => $this->startDate,
            'check_out' => $this->endDate,
            'unit_price' => $this->selectedRoom->unitType->price,
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
                'status' => 'reserved'
            ]);
        }

        $this->createInvoice($booking);

        // return $this->redirect(route('bookings.show', ['booking' => $booking->id]), navigate: true);
        return $this->redirect(route('dashboard', ['dash' => 'home']), navigate: true);
    }

    public function createInvoice($booking){

        $invoice = BookingInvoice::create([
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            'guest_id' => $booking->guest_id,
            'date' => now(),
            'due_date' => $booking->check_out,
            'payment_status' => $booking->payment_status,
            'agent_id' => auth()->user()->id,
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

}
