<?php

namespace Modules\ChannelManager\Http\Controllers\Embed;

use App\Http\Controllers\Controller;
use App\Models\Client\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;

class BookingFormController extends Controller
{
    public function getEmbedConfig()
    {
        $apiKey = env('NDAKO_APP_KEY');
        $apiSecret = env('NDAKO_SECRET_KEY');

        // Return the public API key and any other relevant configuration
        return response()->json([
            'publicKey' => $apiKey,
            'secretKey' => $apiSecret
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function embed()
    {
        $roomTypes = PropertyUnitType::isCompany(current_company()->id)->get();
        
        // Generate the HTML for the available rooms
        return view('channelmanager::embed.booking-form', ['roomTypes' => $roomTypes])->render();
    }

    public function checkAvailability(Request $request)
    {
        
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        // Validate API keys
        $client = ApiClient::where('public_key', $apiKey)->first();

        if (!$client || !hash_equals($client->private_key, $apiSecret)) {
            return response()->json([
                'message' => 'Unauthorized: Invalid API keys.',
            ], 401);
        }

        // Step 1: Validate the request data
    
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
        $rooms = PropertyUnit::isCompany(current_company()->id)->where('capacity', '>=', $people)
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
        return view('channelmanager::embed.available-rooms', ['rooms' => $rooms])->render();

    }

    public function roomDetail($room){
        // Log::info('Room:', ['room' => $room]);
        $room = PropertyUnit::find($room);
    
        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }
    
        return response()->json([
            'name' => $room->name,
            'type' => $room->unitType->name,
            'price' => $room->unitType->price,
            'details' => $room->description,  // Add more fields as necessary    
        ]);
    }
    public function confirmBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:property_units,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'people' => 'required|integer|min:1',
        ]);

        // Ensure the room is still available
        $room = PropertyUnit::find($validated['room_id']);
        $conflictingBooking = Booking::where('property_unit_id', $room->id)
            ->where(function ($query) use ($validated) {
                $query->where('check_in', '<=', $validated['check_out'])
                    ->where('check_out', '>=', $validated['check_in']);
            })
            ->exists();

        if ($conflictingBooking) {
            return response()->json(['success' => false, 'message' => 'Room is no longer available.'], 400);
        }

        // Create booking
        // Booking::create([
        //     'property_unit_id' => $room->id,
        //     'check_in' => $validated['check_in'],
        //     'check_out' => $validated['check_out'],
        //     'people' => $validated['people'],
        // ]);

        return response()->json(['success' => true, 'message' => 'Booking confirmed.']);
    }

}
