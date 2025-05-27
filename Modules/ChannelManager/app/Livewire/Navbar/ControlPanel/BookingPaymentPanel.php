<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\ChannelManager\Models\Booking\BookingPayment;

class BookingPaymentPanel extends ControlPanel
{

    public function mount($invoice = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->filterTypes = [
            'status' => [
                'pending' => 'Pending',    // string filter
                'posted' => 'Posted',
            ],
            'payment_method' => [
                'm-pesa' => 'M-Pesa',
                'cash' => 'Cash',
                'bank' => 'Bank',
                'paystack' => "Paystack"
            ]
        ];
        // $this->showIndicators = true;
            $this->currentPage = "Payments";

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
        ];
    }

    public function actionButtons(): array
    {
        return [
            ActionButton::make('export', 'Export All', 'exportAll', false, "fas fa-download"),
            ActionButton::make('import', 'Import Records', 'importRecords', false, "fas fa-upload"),
        ];
    }

    public function actionDropdowns(): array
    {
        return [
            ActionDropdown::make('export', 'Export', 'exportSelected', false, "fas fa-download"),
            ActionDropdown::make('duplicate', 'Duplicate', 'duplicateItems', false, "fas fa-copy"),
            ActionDropdown::make('delete', 'Delete', 'deleteSelectedItems', false, "fas fa-trash", true, "Do you really want to delete the selected items?"),
        ];
    }

    public function exportSelected(){
        $exportService = new ReportExportService();

        $payments = BookingPayment::isCompany(current_company()->id)
        ->whereIn('id', $this->selected)->get()
        ->map(function ($payment) {

            return [
                'reference' => $payment->invoice->booking->reference,
                // 'booking_reference' => $payment->invoice->booking->reference,
                'invoice' => $payment->invoice->reference,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'due_amount' => $payment->due_amount,
                'payment_date' => $payment->date,
            ];
        });

        $detailedSections = [
            'Payments' => $payments,
        ];

        return $exportService->export("Payments_export", [], $detailedSections);
    }

    public function exportAll(){
        $exportService = new ReportExportService();

        $payments = BookingPayment::isCompany(current_company()->id)->get()
        ->map(function ($payment) {

            return [
                'reference' => $payment->invoice->booking->reference,
                // 'booking_reference' => $payment->invoice->booking->reference,
                'invoice' => $payment->invoice->reference,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'due_amount' => $payment->due_amount,
                'payment_date' => $payment->date,
            ];
        });

        $detailedSections = [
            'Payments' => $payments,
        ];

        return $exportService->export("Payments_export", [], $detailedSections);
    }

    public function importRecords(){
        return $this->redirect(route('import.records', 'mod_payments'), true);
    }

}
