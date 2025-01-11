<?php

namespace Modules\ChannelManager\Services\Connector;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingComConnector
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('channelmanager.bookingcom.api_url');
        $this->apiKey = config('channelmanager.bookingcom.api_key');
    }

    /**
     * Make an HTTP request to the Booking.com API.
     *
     * @param string $method HTTP method (get, post, etc.)
     * @param string $endpoint API endpoint
     * @param array $data Data to send with the request
     * @return array API response in JSON format
     * @throws \Exception
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->timeout(10)
                ->{$method}($this->apiUrl . $endpoint, $data);

            if ($response->failed()) {
                throw new Exception("API error: {$response->status()} - {$response->body()}");
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to communicate with Booking.com API: " . $e->getMessage());
        }
    }

    /**
     * Fetch reservations from the API.
     *
     * @param string|null $updatedSince Optional timestamp to filter reservations
     * @return array
     */
    public function getReservations($updatedSince = null)
    {
        $query = $updatedSince ? ['updated_since' => $updatedSince] : [];
        return $this->makeRequest('get', 'reservations', $query);
    }

    /**
     * Update room availability.
     *
     * @param string $roomId Room ID
     * @param int $availability Number of available units
     * @param float $rate Room rate
     * @param string $date Date to update
     * @return array
     */
    public function updateAvailability($roomId, $availability, $rate, $date)
    {
        return $this->makeRequest('post', 'availability', [
            'room_id' => $roomId,
            'availability' => $availability,
            'rate' => $rate,
            'date' => $date,
        ]);
    }

    /**
     * Fetch room details from the API.
     *
     * @return array
     */
    public function getRooms()
    {
        return $this->makeRequest('get', 'rooms');
    }
}
