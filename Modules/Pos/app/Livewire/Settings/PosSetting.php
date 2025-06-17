<?php

namespace Modules\Pos\Livewire\Settings;

use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSetting as PosPosSetting;
use Modules\RevenueManager\Models\Accounting\Journal;

class PosSetting extends AppSetting
{
    public $pos, $setting;
    public bool $activeDesk = true, $has_automatically_validate_order, $has_maximum_difference_at_closing = false, $has_stripe_payment_terminal, $has_paytm_payment_terminal, $show_product_images, $show_category_images, $has_price_control, $has_employee_login = false;
    public $maximum_difference_at_closing = 0, $selectedPaymentMethod, $product_prices='tax-included';
    public array $restaurants = [], $paymentMethods = [], $deskPaymentMethods = [], $saleJournals = [], $invoiceJournals = [], $productPrice = [];

    public function mount($pos = null, $setting = null){
        $this->pos = current_company()->restaurants()->first();
        $setting = $this->pos->setting;
        $this->setting = $setting;
        $this->deskPaymentMethods = $setting->payment_methods ?? [];
        $this->has_automatically_validate_order = $setting->has_automatically_validate_order;
        $this->has_maximum_difference_at_closing = $setting->has_maximum_difference_at_closing;
        $this->has_stripe_payment_terminal = $setting->has_stripe_payment_terminal;
        $this->has_paytm_payment_terminal = $setting->has_paytm_payment_terminal;
        $this->show_product_images = $setting->show_product_images;
        $this->show_category_images = $setting->show_category_images;
        $this->has_price_control = $setting->has_price_control;
        $this->maximum_difference_at_closing = $setting->maximum_difference_at_closing;
        $this->product_prices = $setting->product_prices;
        // $this->activeDesk = $setting->active_desk;



        $this->restaurants = toSelectOptions(Pos::isCompany(current_company()->id)->get(), 'id', 'name');
        $paymentMethods = Journal::whereNotIn('type', ['miscellaneous', 'sale', 'purchase'])->isCompany(current_company()->id)->get();
        $this->paymentMethods = toSelectOptions($paymentMethods, 'id', 'name');

        $this->saleJournals = toSelectOptions(Journal::isType('sale')->isCompany(current_company()->id)->get(), 'id', 'name');
        $this->invoiceJournals = toSelectOptions(Journal::isType('sale')->isCompany(current_company()->id)->get(), 'id', 'name');

        $productPrice = [
            ['id' => 'tax-included', 'label' => 'Tax Included Price'],
            ['id' => 'tax-excluded', 'label' => 'Tax Excluded Price'],
        ];
        $this->productPrice = toSelectOptions($productPrice, 'id', 'label');

    }

    public function blocks() : array
    {
        return [
            Block::make('front-desk', __('Front Desk'))->component('app::blocks.templates.pos-header'),
            Block::make('payments', __('Payments')),
            Block::make('interface', __('Front Desk Interface')),
            // Block::make('accounting', __('Accounting')),
            // Block::make('sales', __('Sales')),
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
            Box::make('room-price', "Product Prices", '', "Product prices on receipts", 'pricing', false, "", null),
        ];
    }

    public function inputs() : array
    {
        return [
            BoxInput::make('payment-method', "", 'tag', 'selectedPaymentMethod', 'payments', '', false, ['options' => $this->paymentMethods, 'data' => $this->deskPaymentMethods, 'action' => 'addPaymentMethod', 'delete' => 'removePaymentMethod'])->component('app::blocks.boxes.input.tag.journal-payment'),

            BoxInput::make('maximum-difference', "", 'price', 'maximum_difference_at_closing', 'maximum-difference', '', false, [], $this->has_maximum_difference_at_closing)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('hide-image', "Show products images", 'tag', 'show_product_images', 'hide-pictures', '', false, [])->component('app::blocks.boxes.input.checkbox.simple'),
            BoxInput::make('hide-image', "Show categories images", 'tag', 'show_category_images', 'hide-pictures', '', false, [])->component('app::blocks.boxes.input.checkbox.simple'),
            BoxInput::make('default-order-journal', "Orders", 'select', 'order_journal_id', 'default-journal', '', false, $this->saleJournals),
            BoxInput::make('default-invoice-journal', "Invoices", 'select', 'invoice_journal_id', 'default-journal', '', false, $this->invoiceJournals),
            BoxInput::make('desk-pods', "", 'select', 'payment_method', 'desk-pod', '', false, $this->invoiceJournals),
            BoxInput::make('unit-price', "", 'select', 'product_prices', 'room-price', '', false, $this->productPrice),
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

    public function addPaymentMethod()
    {
        $this->validate([
            'selectedPaymentMethod' => 'required|exists:journals,id',
        ]);

        if (is_null($this->setting)) {
            // We're on the create page — use array to collect taxes
            if (in_array($this->selectedPaymentMethod, $this->deskPaymentMethods)) {
                session()->flash('error', 'This payment method has already been added.');
                return;
            }

            $this->deskPaymentMethods[] = $this->selectedPaymentMethod;
        } else {
            // We're on the edit page — update the product directly
            $existingMethods = $this->setting->payment_methods ?? [];

            if (in_array($this->selectedPaymentMethod, $existingMethods)) {
                session()->flash('error', 'This payment method has already been added to this restaurant.');
                return;
            }

            $existingMethods[] = $this->selectedPaymentMethod;
            $this->setting->payment_methods = $existingMethods;
            $this->setting->save();

            session()->flash('success', 'Payment method added to restaurant.');
        }

        $this->selectedPaymentMethod = null; // reset dropdown
        $this->deskPaymentMethods = $this->setting->payment_methods ?? [];

    }
    public function removePaymentMethod($methodId)
    {
        if (is_null($this->setting)) {
            // Create mode – work with temporary array
            $this->deskPaymentMethods = array_filter(
                $this->deskPaymentMethods,
                fn ($id) => $id != $methodId
            );
        } else {
            // Edit mode – update the saved product directly
            $existingMethods = $this->setting->payment_methods ?? [];

            $filtered = array_filter($existingMethods, fn ($id) => $id != $methodId);

            $this->setting->payment_methods = array_values($filtered); // reindex to avoid gaps
            $this->setting->save();

            $this->deskPaymentMethods = $this->setting->payment_methods ?? [];
            session()->flash('success', 'Payment method removed from restaurant.');
        }
    }

    #[On('save')]
    public function save(){
        // $this->validate();

        $setting = PosPosSetting::isPos($this->pos->id)->first();
        $setting->update([
            'has_automatically_validate_order' => $this->has_automatically_validate_order,
            'has_maximum_difference_at_closing' => $this->has_maximum_difference_at_closing,
            'maximum_difference_at_closing' => $this->maximum_difference_at_closing,
            'has_stripe_payment_terminal' => $this->has_stripe_payment_terminal,
            'has_paytm_payment_terminal' => $this->has_paytm_payment_terminal,
            'show_product_images' => $this->show_product_images,
            'show_category_images' => $this->show_category_images,
            'has_price_control' => $this->has_price_control,
            'has_employee_login' => $this->has_employee_login,
        ]);
        $setting->save();


        // notify()->success('Updates saved!');
        $this->dispatch('undo-change');

        LivewireAlert::title('Updates saved!')
        ->text('Your updates have been saved.')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();
    }

    public function updated(){
        $this->dispatch('change');
    }
}
