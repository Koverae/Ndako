<?php

namespace Modules\ChannelManager\Http\Controllers\Api\Connector;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\ChannelManager\Services\Connector\BookingComConnector;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingComConnector $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a list of reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $reservations = $this->bookingService->getReservations();
            return response()->json(['reservations' => $reservations]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update room availability.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvailability(Request $request)
    {
        try {
            $this->validate($request, [
                'room_id' => 'required|string',
                'availability' => 'required|integer|min:0',
                'rate' => 'required|numeric|min:0',
                'date' => 'required|date',
            ]);

            $response = $this->bookingService->updateAvailability(
                $request->input('room_id'),
                $request->input('availability'),
                $request->input('rate'),
                $request->input('date')
            );

            return response()->json(['message' => 'Availability updated successfully', 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
