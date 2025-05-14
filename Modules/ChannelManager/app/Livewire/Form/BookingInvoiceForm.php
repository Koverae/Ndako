<?php

namespace Modules\ChannelManager\Livewire\Form;

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
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\PropertyUnit;

class BookingInvoiceForm extends LightWeightForm
{
    use ActionBarButtonTrait;

    public BookingInvoice $invoice;
    public $reference, $booking, $guest, $agent, $room, $invoiceDate, $dueDate, $startDate, $endDate, $paymentStatus = 'unpaid';
    public array $guestOptions = [], $roomOptions = [];

    protected $rules = [
        'guest' => 'nullable|integer|exists:users,id',
        'room' => 'nullable|integer|exists:property_units,id',
        'invoiceDate' => 'required|date',
        'dueDate' => 'required|date|after_or_equal:invoiceDate',
    ];
    public function mount($invoice = null){
        if($invoice){
            $this->invoice = $invoice;
            $this->isInvoice = true;
            $this->reference = $invoice->reference;
            $this->booking = $invoice->booking_id;
            $this->status = $invoice->status;
            $this->paymentStatus = $invoice->payment_status;
            $this->guest = $invoice->guest_id;
            $this->agent = $invoice->agent_id;
            $this->room = $invoice->booking->property_unit_id;
            $this->startDate = $invoice->booking->check_in;
            $this->endDate = $invoice->booking->check_out;
            $this->invoiceDate = $invoice->date;
            $this->dueDate = $invoice->due_date ?? now()->format('Y-m-d');
            $this->totalAmount = $invoice->total_amount;
            $this->dueAmount = $invoice->due_amount;

            if($invoice->status == 'draft'){
                $this->reference = 'Draft';
            }else{
                $this->reference = $invoice->reference;
            }
        }

        $this->guestOptions = toSelectOptions(Guest::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->roomOptions = toSelectOptions(PropertyUnit::isCompany(current_company()->id)->get(), 'id', 'name');
    }
    // Action Bar Button
    public function actionBarButtons() : array
    {
        $type = $this->status;

        $buttons =  [
            ActionBarButton::make('confirm', __('Confirm'), 'confirm()', "draft", $this->status != 'draft'),
            ActionBarButton::make('credit-note', __('Credit Note'), 'credit()', "", $this->status != 'posted'),
            ActionBarButton::make('preview', __('Preview'), 'preview()', "", $this->status != 'posted'),
            ActionBarButton::make('pay', __('Pay'), 'pay()', "posted", $this->status !== 'posted' || $this->paymentStatus == 'paid'),
            ActionBarButton::make('send-email', __('Send & Print'), 'sendEmail()', "posted", $this->status != 'posted'),
            ActionBarButton::make('cancel', __('Cancel'), 'cancel()', 'blocked', $this->status != 'draft'),
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
            StatusBarButton::make('draft', __('Draft'), 'draft'),
            StatusBarButton::make('posted', __('Posted'), 'posted'),
        ];
    }


    public function capsules() : array
    {
        return [
            Capsule::make('booking', __('Booking'), __('Reservations linked to this invoice'), 'link', 'fa fa-bookmark', route('bookings.show', ['booking' => $this->booking])),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('guests', 'Guest', 'select', 'guest', 'left', 'none', 'none', "", "", $this->guestOptions, true),
            Input::make('unit', 'Room', 'select', 'room', 'left', 'none', 'none', "", "", $this->roomOptions, true),
            Input::make('invoicing-date', 'Invoice Date', 'date', 'invoiceDate', 'right', 'none', 'none', "", ""),
            Input::make('due-date', 'Due Date', 'date', 'dueDate', 'right', 'none', 'none', "", ""),
            Input::make('agents', 'Agent', 'select', 'agent', 'right', 'none', 'none', "", "", $this->guestOptions),
        ];
    }

    public function confirm(){
        $this->validate();
        $this->invoice->update([
            'status' => 'posted'
        ]);
    }

    public function pay(){
        $this->dispatch('openModal', component: 'channelmanager::modal.add-invoice-payment-modal', arguments: ['invoice' => $this->invoice->id]);
    }

    public function sendEmail(){

        $subject = ['{property_name}', '{reference}'];
        $subjectReplace = [current_property()->name, $this->invoice->reference];

        $content = ['{total_amount}', '{reference}', '{guest_name}', '{company_name}'];
        $contentReplace = [
            format_currency($this->invoice->total_amount ?? 0),
            $this->invoice->reference,
            $this->invoice->guest->name ?? 'Arden BOUET',
            current_property()->name,
        ];
        $data = [
            'total_amount' => format_currency($this->invoice->total_amount ?? 0),
            'reference' => $this->invoice->booking->reference,
            'invoice_reference' => $this->invoice->reference,
            'date' => $this->invoice->date,
            'payment_method' => 'M-Pesa',
            'company_phone' => '+254 123 456 789',
        ];

        $this->dispatch('openModal', component: 'app::modal.send-by-email-modal', arguments: [
            'templateId' => 11, // Booking Invoice
            'model' => [
                'guest_id' => (int) $this->invoice->guest_id, // Ensure integer
                'booking_id' => (int) $this->invoice->booking_id, // Ensure integer
            ],
            // 'subjectSearch' => $subject,
            'subjectReplace' => $subjectReplace,
            // 'contentSearch' => $content,
            'contentReplace' => $contentReplace,
            'data' => $data,
        ]);
    }

}
