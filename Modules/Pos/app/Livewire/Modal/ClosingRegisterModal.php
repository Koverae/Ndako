<?php

namespace Modules\Pos\Livewire\Modal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use LivewireUI\Modal\ModalComponent;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSession;

class ClosingRegisterModal extends ModalComponent
{
    public Pos $pos;
    public PosSession $session;
    public $closing_cash = 0, $totalCash = 0, $totalPaymentCash = 0, $differenceCash = 0, $cashInOut = 0, $totalCard = 0, $totalPaystack = 0;
    public $closing_note = '';
    
    public function mount(Pos $pos, PosSession $session)
    {
        $this->pos = $pos;
        $this->session = $session;
        if (!$this->pos) {
            abort(404, 'POS or session not found');
        }

        $cashPayments = $this->session->orders()
            ->where('status', 'receipt')
            ->with(['payments' => function ($query) {
                $query->where('payment_method', 'cash');
            }])
            ->get()
            ->flatMap(function ($order) {
                return $order->payments;
            })
            ->sum('amount');

        $this->totalPaymentCash = $cashPayments;
        $this->totalCash = $this->session->starting_balance + $cashPayments;

        $cardPayments = $this->session->orders()
            ->where('status', 'receipt')
            ->with(['payments' => function ($query) {
                $query->whereIn('payment_method', ['card','mobile-money', 'mpesa']);
            }])
            ->get()
            ->flatMap(function ($order) {
                return $order->payments;
            })
            ->sum('amount');
        $this->totalCard = $cardPayments;

        $paystackPayments = $this->session->orders()
            // ->where('status', 'receipt')
            ->with(['payments' => function ($query) {
                $query->where('payment_method', 'paystack');
            }])
            ->get()
            ->flatMap(function ($order) {
                return $order->payments;
            })
            ->sum('amount');
        $this->totalPaystack = $paystackPayments;
        
        $this->differenceCash = (float) $this->closing_cash - $this->totalCash;

    }

    public function updatedClosingCash($value)
    {
        $this->differenceCash = (float) $value - $this->totalCash;
    }

    public function showDailySales()
    {
        // Prepare data for PDF
        $pdfData = [
            'company_name' => current_company()->name ?? 'Mamba Resorts',
            'session' => $this->session,
            'cashPayments' => $this->totalPaymentCash,
            'cardPayments' => $this->totalCard,
            'paystackPayments' => $this->totalPaystack,
        ];
        Log::info('PDF data prepared', ['pdfData' => $pdfData]);

        // Generate PDF
        $pdf = Pdf::loadView('app::pdf.reports.daily-sales', $pdfData);

        // Return PDF as download response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "Daily Sales Report.pdf");
    }

    public function render()
    {
        return view('pos::livewire.modal.closing-register-modal');
    }
}
