<?php

namespace Modules\App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DarajaCallbackController extends Controller
{
    public function stkCallback(Request $request)
    {
        Log::info('STK Callback:', $request->all());

        // Process response: check success, update payments table, etc.
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function b2cResult(Request $request)
    {
        Log::info('B2C Result:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function b2cTimeout(Request $request)
    {
        Log::warning('B2C Timeout:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function c2bConfirmation(Request $request)
    {
        Log::info('C2B Confirmation:', $request->all());

        // Save payment data (e.g. payment log, link to tenant invoice, etc)
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function c2bValidation(Request $request)
    {
        Log::info('C2B Validation:', $request->all());

        // Optionally validate details before accepting payment
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function reversal(Request $request)
    {
        Log::info('Reversal Callback:', $request->all());

        // Handle reversal result: update payment status, log, etc.
        return response()->json(['ResultCode' => 0, 'ResponseDescription' => 'Reversal Received']);
    }
    
}
