<?php

namespace Modules\ChannelManager\Http\Controllers\Api\V1\Embed;

use App\Http\Controllers\Controller;
use App\Models\Client\ApiClient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Entities\Journal;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\RevenueManager\Services\Pricing\RateService;

class BookingFormController extends Controller
{
    public function getEmbedConfig(Request $request)
    {
        $publicKey = $request->header('X-API-Key');
        // $origin = $request->header('Origin') ?? parse_url($request->headers->get('Referer'), PHP_URL_HOST);
    
        $client = ApiClient::where('public_key', $publicKey)->first();
    
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Invalid API key.'], 403);
        }

        $apiKey = $client->public_key;
        $apiSecret = $client->private_key;

        // Return the public API key and any other relevant configuration
        return response()->json([
            'publicKey' => $apiKey,
            'secretKey' => $apiSecret
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function embed(Request $request)
    {
        $client = $request->get('api_client');
    
        $roomTypes = PropertyUnitType::with(['property'])->isCompany($client->company_id)->get();
        
        // Generate the HTML for the available rooms
        return view('channelmanager::embed.booking-form', ['roomTypes' => $roomTypes])->render();
    }

    public function checkAvailability(Request $request)
    {
        
        $client = $request->get('api_client');

        // Validating that check_in is a required date, it should be a valid date, and it must be today or in the future
        // The 'after_or_equal' rule ensures that check_in cannot be a past date
        // This also ensures that check_in is in a valid date format (Y-m-d)
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today', // Date cannot be in the past
            'check_out' => 'required|date|after:check_in', // Check-out must be after check-in
            'room_type' => 'nullable|integer|exists:property_unit_types,id', // Ensure at least 1 person is specified
            'people' => 'required|integer|min:1', // Ensure at least 1 person is specified
        ]);
        
        $type = $validated['room_type'];
        $people = $validated['people'];
        $startDate = $validated['check_in'];
        $endDate = $validated['check_out'];
    
        // Step 2: Business logic - Check if the check-out date is at least one day after the check-in date
        // The 'after:check_in' validation ensures that, but it's worth mentioning again in this context
        if ($request->check_in === $request->check_out) {
            return response()->json([
                'available' => false,
                'message' => 'Check-out date must be at least one day after the check-in date.',
            ], 400); // 400 Bad Request
        }
    
        // Step 3: Custom logic - Business rules for availability
        // Example: Ensure that the number of people is within a certain limit (for example, 1-10 people)
        if ($validated['people'] > 10) {
            return response()->json([
                'available' => false,
                'message' => 'We only accept bookings for up to 10 people.',
            ], 400); // 400 Bad Request
        }
    
        // Step 4: Simulate availability check
        // Normally, you'd check the availability based on the dates and number of people in a database, but for now, we'll simulate
        
        // Step 1: Fetch rooms that fit the capacity criteria
        $rooms = PropertyUnit::isCompany($client->company_id)->where('capacity', '>=', $people)
        ->with(['unitType']) // Eager load related price table
        ->when($type, function ($query, $type){
            $query->where('property_unit_type_id', $type);
        })
        ->get();

        // Step 3: Filter rooms not available for the specified date range
        $availableRooms = $rooms->filter(function ($room) use ($startDate, $endDate) {
            return !Booking::where('property_unit_id', $room->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('check_in', '<=', $endDate)
                        ->where('check_out', '>=', $startDate);
                })
                ->exists();
        })->values(); // Reindex the filtered collection
        
        // Store available rooms in session
        Cache::put('available_rooms', $availableRooms, now()->addMinutes(10));

        // Step 5: Return response based on availability
        if ($availableRooms->isNotEmpty()) {
            return response()->json([
                'available' => true,
                'message' => $availableRooms->count() .' rooms are available for the selected dates!',
                'data' => $availableRooms,
            ], 200); // 200 OK
        } else {
            return response()->json([
                'available' => false,
                'message' => 'No availability for the selected dates.',
            ], 200); // 200 OK (valid response even if no rooms are available)
        }
    }

    public function availableRoomsHtml(Request $request)
    {
        $rooms = Cache::get('available_rooms', collect());

        // Generate the HTML for the available rooms
        return view('channelmanager::embed.available-rooms', [
            'rooms' => $rooms
            ])->render();

    }

    public function roomDetail($room){
        // Log::info('Room:', ['room' => $room]);
        $room = PropertyUnit::find($room);
    
        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }
    
        // Cache::put('available_rooms', $room, now()->addMinutes(10));
        return response()->json([
            'id' => $room->id,
            'name' => $room->name,
            'type' => $room->unitType->name,
            'price' => $room->unitType->price,
            'details' => $room->description,  // Add more fields as necessary    
        ]);
    }

    public function confirmBookingHtml(Request $request, $roomId){
        
        $room = PropertyUnit::find($roomId);
        $checkIn = Carbon::parse($request->query('check_in'));
        $checkOut = Carbon::parse($request->query('check_out'));
        $nights = $checkIn->diffInDays($checkOut);
        // $callback_url = $request->query('callback_url');

        $rateService = new RateService();
        $totalPrice = $rateService->getOptimalPricing($room->unitType->id, $nights);
        
        // Generate the HTML for the available rooms
        return view('channelmanager::embed.checkout-section', [
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'nights' => $nights,
            'totalPrice' => $totalPrice,
            ])->render();
    }

    
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'room_id' => 'required|exists:property_units,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'required|numeric',
            'callback' => 'nullable'
        ]);

        // Process the booking and Guest identification or registration

        
        // Save callback in session or encode it into redirect URL
        Cache::put('callback_url', $request->callback_url, now()->addMinutes(10));

        // 🔁 Actual redirect
        // return redirect()->route('api.booking.confirm', ['id' => 1]);
        return [
            'callback_url' => 'http://localhost::5000/thank-you'
        ];
        
    }

    public function confirm(Request $request, $id)
    {
        $reservation = User::findOrFail($id);
    
        // Simulate or verify payment (e.g., via Paystack webhook or ref)
        // $verified = $this->verifyPayment($reservation);
    
        // Retrieve the original success URL
        $successUrl = Cache::get('callback_url', 'http://thanks.com');
    
        // Optional: Add info to query string
        $query = http_build_query([
            'name' => $reservation->name,
            'email' => $reservation->email,
            'amount' => $reservation->total_price,
            'ref' => 'TXN123ABC' // or actual transaction ref
        ]);
    
        return redirect()->away($successUrl . '?' . $query);
    }

    public function confirmBooking(Request $request)
    {
        
        $client = $request->get('api_client');

        // $request->validate([
        //     'room_id' => 'required|integer|exists:property_units,id',
        //     'check_in' => 'required|date|after_or_equal:today',
        //     'check_out' => 'required|date|after:check_in',
        //     'people' => 'required|integer|min:1',
        //     'callback_url' => 'nullable'
        // ]);

        // // Ensure the room is still available
        $room = PropertyUnit::find($request->room_id);
        if(!$room){
            return response()->json([
                'success' => false, 
                'message' => 'The selected room does not exist.',
            ]);
        }

        $conflictingBooking = Booking::where('property_unit_id', $room->id)
            ->where(function ($query) use ($request) {
                $query->where('check_in', '<=', $request->check_out)
                    ->where('check_out', '>=', $request->check_in);
            })
            ->exists();

        if ($conflictingBooking) {
            return response()->json([
                'success' => false, 
                'message' => 'Room is no longer available.'
            ], 400);
        }

        // Create Or Get Guest
        $guest = Guest::firstOrCreate(
            ['company_id' => $client->company_id, 'email' => $request->email],
            ['name' => $request->name, 'phone' => $request->phone]
        );

        // Create booking
        $rateService = new RateService();

        $paidAmount = 0;

        $booking = Booking::create([
            'company_id' => $client->company_id,
            'property_unit_id' => $room->id,
            'guest_id' => $guest->id,
            // 'agent_id' => Auth::user()->id,
            'guests' => $request->people,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'unit_price' => $rateService->getDefaultRate($room->unitType->id)->price,
            'paid_amount' => $paidAmount,
            'due_amount' => $request->total_amount,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
            'payment_status' => 'unpaid',
            'invoice_status' => 'not_invoiced',
            // Add the check-in and check-out status fields
            'check_in_status' => 'pending', // Check if check-in is today
            'check_out_status' => 'pending', // Initial status
            'source' => 'website'
        ]);
        $booking->save();

        $booking->unit->update([
            'status' => 'reserved'
        ]);
        $this->createInvoice($booking);
        
        return response()->json([
            'success' => true, 
            'message' => 'Booking confirmed.',
            'redirect_url' => $request->callback_url
        ]);
    }
    
    public function createInvoice($booking){

        $invoice = BookingInvoice::create([
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            'guest_id' => $booking->guest_id,
            'date' => now(),
            'due_date' => $booking->check_out,
            'payment_status' => $booking->payment_status,
            // 'agent_id' => Auth::user()->id,
            'terms' => $booking->terms,
            'total_amount' => $booking->total_amount,
            'paid_amount' => $booking->paid_amount,
            'due_amount' => $booking->due_amount,
            'status' => 'draft',
            'to_checked' => false,
        ]);
        $invoice->save();

        $booking->update([
            'invoice_status' => 'invoiced'
        ]);
    }

}
