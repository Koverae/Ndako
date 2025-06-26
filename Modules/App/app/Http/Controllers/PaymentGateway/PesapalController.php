<?php

namespace Modules\App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\App\Services\PaymentGateway\PesapalService;

class PesapalController extends Controller
{
    public function handleCallback(Request $request)
    {
        // User is redirected back here after payment
        $trackingId = $request->get('tracking_id');

        if (!$trackingId) {
            return response('Missing tracking ID', 400);
        }

        $status = (new PesapalService())->getTransactionStatus($trackingId);

        // TODO: Update your order based on $status['payment_status_description']

        return view('pesapal.callback', [
            'status' => $status
        ]);
    }

    public function handleIPN(Request $request)
    {
        $trackingId = $request->get('tracking_id');

        if (!$trackingId) {
            Log::warning('IPN received without tracking_id', $request->all());
            return response('Invalid IPN', 400);
        }

        $status = (new PesapalService())->getTransactionStatus($trackingId);

        // Update your DB here
        Log::info('IPN received for tracking_id ' . $trackingId, $status);

        return response('IPN Received', 200);
    }
}
