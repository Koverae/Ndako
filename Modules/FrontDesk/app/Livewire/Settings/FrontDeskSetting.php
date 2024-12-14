<?php

namespace Modules\FrontDesk\Livewire\Settings;

use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\FrontDesk\Models\Desk\DeskSetting;
use Modules\FrontDesk\Models\Desk\FrontDesk;
use Modules\RevenueManager\Models\Accounting\Journal;

class FrontDeskSetting extends AppSetting
{
    public $front, $setting;
    public bool $activeDesk = true, $has_automatically_validate_order, $has_maximum_difference_at_closing, $has_stripe_payment_terminal, $has_paytm_payment_terminal, $show_property_images, $show_category_images, $has_price_control;
    public $maximum_difference_at_closing;
    public array $frontDesks = [], $paymentMethods = [], $deskPaymentMethods = [], $saleJournals = [], $invoiceJournals = [], $unitPrice = [];

    public function mount(FrontDesk $front, $setting){
        $this->front = $front;
        $this->setting = $setting;
        $setting = DeskSetting::isDesk($front->id)->first();
        $this->has_automatically_validate_order = $setting->has_automatically_validate_order;
        $this->has_maximum_difference_at_closing = $setting->has_maximum_difference_at_closing;
        $this->has_stripe_payment_terminal = $setting->has_stripe_payment_terminal;
        $this->has_paytm_payment_terminal = $setting->has_paytm_payment_terminal;
        $this->show_property_images = $setting->show_property_images;
        $this->show_category_images = $setting->show_category_images;
        $this->has_price_control = $setting->has_price_control;
        // $this->setting = $setting->setting;



        $this->frontDesks = toSelectOptions(FrontDesk::isCompany(current_company()->id)->get(), 'id', 'name');
        $paymentMethods = [
            ['id' => 'cash', 'label' => $this->front->setting],
            ['id' => 'card', 'label' => 'Card'],
            ['id' => 'm-pesa', 'label' => 'M-Pesa'],
        ];
        $this->paymentMethods = toSelectOptions($paymentMethods, 'id', 'label');
        $deskPaymentMethods = [
            ['id' => 'cash', 'label' => 'Cash'],
            ['id' => 'card', 'label' => 'Card'],
            ['id' => 'm-pesa', 'label' => 'M-Pesa'],
        ];
        $this->deskPaymentMethods = toSelectOptions($deskPaymentMethods, 'id', 'label');

        $this->saleJournals = toSelectOptions(Journal::isType('sale')->isCompany(current_company()->id)->get(), 'id', 'name');
        $this->invoiceJournals = toSelectOptions(Journal::isType('sale')->isCompany(current_company()->id)->get(), 'id', 'name');
        
        $unitPrice = [
            ['id' => 'included', 'label' => 'Tax-included Price'],
            ['id' => 'excluded', 'label' => 'Tax-excluded Price'],
        ];
        $this->unitPrice = toSelectOptions($unitPrice, 'id', 'label');
    
    }

    public function blocks() : array
    {
        return [
            Block::make('front-desk', __('Front Desk'))->component('app::blocks.templates.pos-header'),
            Block::make('payments', __('Payments')),
            Block::make('interface', __('Front Desk Interface')),
            Block::make('accounting', __('Accounting')),
            Block::make('sales', __('Sales')),
            Block::make('pricing', __('Pricing')),
        ];
    }

    public function boxes() : array
    {
        return [
            Box::make('payments', "Payment Methods", '', "Payment methods available", 'payments', false, "", null),
            Box::make('validate-order', "Automatically validate order", 'has_automatically_validate_order', "Automatically validates orders paid with a payment terminal.", 'payments', true, "", null),
            Box::make('maximum-difference', "Set Maximum Difference", 'has_maximum_difference_at_closing', "Set a maximum difference allowed between the expected and counted money during the closing of the session.", 'payments', true, "", null),
            // Interface
            Box::make('log-employee', "Allow to log and switch between selected Employees.", 'has_employee_login', "Allow to log and switch between selected Employees.", 'interface', true, "", null, "Employees can scan their badge or enter a PIN to log in to a Front Desk session. This credentials are configurable in the *HR Settings* tab in the employee form."),
            Box::make('hide-pictures', "Hide pictures in Front Desk", '', "Self-ordering interfaces are not impacted.", 'interface', false, "", null),
            // Accounting
            Box::make('default-journal', "Default Journals", '', "Default journals for orders and invoices.", 'accounting', false, "", null),
            // Sales
            Box::make('desk-pod', "Desk Pods", '', "Sales are reported to the following desk pod.", 'sales', false, "", null, "A Desk Pod is a small, agile team or unit operating at the front desk. Each pod has defined roles, responsibilities, and performance metrics, designed to ensure smooth operations and enable clear tracking of productivity."),
            // Pricing
            Box::make('price-control', "Price Control", 'has_price_control', "Restrict price modification to managers.", 'pricing', true, "", null, "Only users with Manager access rights for Front Desk app can modify the room/unit prices on orders."),
            Box::make('room-price', "Room/Unit Prices", '', "Room/Unit prices on receipts", 'pricing', false, "", null),
        ];
    }

    public function inputs() : array
    {
        return [
            BoxInput::make('payment-method', "", 'tag', 'payment_method', 'payments', '', false, ['options' => $this->paymentMethods, 'data' => $this->deskPaymentMethods]),
            BoxInput::make('maximum-difference', "", 'price', 'maximum_difference', 'maximum-difference', '', false, [], $this->has_maximum_difference_at_closing)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('hide-image', "Show room/unit images", 'tag', 'show_property_images', 'hide-pictures', '', false, [])->component('app::blocks.boxes.input.checkbox.simple'),
            BoxInput::make('hide-image', "Show room/unit images", 'tag', 'show_category_images', 'hide-pictures', '', false, [])->component('app::blocks.boxes.input.checkbox.simple'),
            BoxInput::make('default-order-journal', "Orders", 'select', 'order_journal_id', 'default-journal', '', false, $this->saleJournals),
            BoxInput::make('default-invoice-journal', "Invoices", 'select', 'invoice_journal_id', 'default-journal', '', false, $this->invoiceJournals),
            BoxInput::make('desk-pods', "", 'select', 'payment_method', 'desk-pod', '', false, $this->invoiceJournals),
            BoxInput::make('unit-price', "", 'select', 'unit_price_receipt', 'room-price', '', false, $this->unitPrice),
        ];
    }

    // Boxes Actions
    public function actions(): array
    {
        return [
            BoxAction::make('payment-methods', 'payments', __('Payment Methods'), 'link', 'bi-arrow-right', "", []),
        ];
    }

    public function closeSession(){
        $this->activeDesk = false;
    }

    #[On('save')]
    public function save(){
        // $this->validate();

        $setting = DeskSetting::isDesk($this->front->id)->first();
        $setting->update([
            'has_automatically_validate_order' => $this->has_automatically_validate_order,
            'has_maximum_difference_at_closing' => $this->has_maximum_difference_at_closing,
            'has_stripe_payment_terminal' => $this->has_stripe_payment_terminal,
            'has_paytm_payment_terminal' => $this->has_paytm_payment_terminal,
            'show_property_images' => $this->show_property_images,
            'show_category_images' => $this->show_category_images,
            'has_price_control' => $this->has_price_control,
        ]);
        $setting->save();
        

        cache()->forget('settings');

        // notify()->success('Updates saved!');
        $this->dispatch('undo-change');
    }

    public function updated(){
        $this->dispatch('change');
    }
}
