<?php
namespace Modules\Properties\Services;

use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Properties\Models\Property\ExtraService\ExtraService;
use Modules\Properties\Models\Property\ExtraService\GuestService;
use Modules\Properties\Models\Property\ExtraService\GuestServiceItem;

class InRoomService{

    /**
     * Create a new service.
     *
     * @param array $data
     * @return ExtraService
     */
    public function createService(array $data): ExtraService
    {
        return ExtraService::create($data);
    }

    /**
     * Update an existing service.
     *
     * @param ExtraService $service
     * @param array $data
     * @return ExtraService
     */
    public function updateService(ExtraService $service, array $data): ExtraService
    {
        $service->update($data);
        return $service;
    }

    /**
     * Soft delete a service (mark as deleted but keep record in DB).
     *
     * @param ExtraService $service
     * @return bool|null
     */
    public function deleteService(ExtraService $service): ?bool
    {
        return $service->delete();
    }

    /**
     * Restore a soft deleted service.
     *
     * @param int $serviceId
     * @return ExtraService
     */
    public function restoreService(int $serviceId): ExtraService
    {
        $service = ExtraService::onlyTrashed()->findOrFail($serviceId);
        $service->restore();
        return $service;
    }

    /**
     * Permanently delete a service from the database.
     *
     * @param int $serviceId
     * @return bool|null
     */
    public function forceDeleteService(int $serviceId): ?bool
    {
        $service = ExtraService::onlyTrashed()->findOrFail($serviceId);
        return $service->forceDelete();
    }


    /**
     * Create a new service order for a booking.
     *
     * @param int $bookingId
     * @param array $services (Array of ['service_id' => x, 'quantity' => y])
     * @return GuestService|null
     */
    public function createGuestService(int $bookingId, array $services)
    {
        $booking = Booking::findOrFail($bookingId);

        // Create the order
        $order = GuestService::create([
            'booking_id'   => $booking->id,
            'guest_id'     => $booking->guest_id,
            'status'       => 'pending',
            'total_amount' => 0, // Will be calculated
        ]);

        // Add services to the order
        $totalAmount = 0;
        foreach ($services as $serviceData) {
            $service = ExtraService::findOrFail($serviceData['service_id']);
            $quantity = $serviceData['quantity'] ?? 1;
            $price = $service->price * $quantity;

            GuestServiceItem::create([
                'service_order_id' => $order->id,
                'service_id'       => $service->id,
                'quantity'         => $quantity,
                'price'            => $price,
            ]);

            $totalAmount += $price;
        }

        // Update order total
        $order->update(['total_amount' => $totalAmount]);

    }

    /**
     * Approve a service order (if staff confirmation is required).
     *
     * @param int $orderId
     * @return bool
     */
    public function approveOrder(int $orderId): bool
    {
        $order = GuestService::findOrFail($orderId);
        if ($order->status !== 'pending') return false;

        $order->update(['status' => 'approved']);
        return true;
    }

    /**
     * Reject a service order (if staff confirmation is required).
     *
     * @param int $orderId
     * @return bool
     */
    public function rejectOrder(int $orderId): bool
    {
        $order = GuestService::findOrFail($orderId);
        if ($order->status !== 'pending') return false;

        $order->update(['status' => 'rejected']);
        return true;
    }

    /**
     * Cancel a service order.
     *
     * @param int $orderId
     * @return bool
     */
    public function cancelOrder(int $orderId): bool
    {
        $order = GuestService::findOrFail($orderId);
        if ($order->status === 'completed') return false; // Cannot cancel a completed order

        $order->update(['status' => 'canceled']);
        return true;
    }

    /**
     * Complete a service order after it's fulfilled.
     *
     * @param int $orderId
     * @return bool
     */
    public function completeOrder(int $orderId): bool
    {
        $order = GuestService::findOrFail($orderId);
        if ($order->status !== 'approved') return false; // Must be approved first

        $order->update(['status' => 'completed']);
        return true;
    }
}
