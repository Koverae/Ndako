<?php
namespace Modules\Settings\Handlers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Settings\Models\WorkItem;

class WorkItemHandler extends Component
{


    #[On('reservation-confirmed')]
    public function handleReservationCreated($reservation)
    {
        // Create tasks for relevant roles
        WorkItem::create([
            'title' => "Prepare Room #{$reservation->name}",
            'description' => "Ensure the room is ready for guest {$reservation->guest_name}.",
            'type' => 'task',
            'status' => 'pending',
            'priority' => 'medium',
            // 'related_id' => $reservation->id,
            // 'assigned_to' => 1, // Default to housekeeping team
            'created_by' => null,
        ]);

        WorkItem::create([
            'title' => 'Deep clean and prepare room',
            'type' => 'task',
            'priority' => 'medium',
            'status' => 'pending',
            'description' => "Room: {$reservation->name} - Check-in Date: ".\Carbon\Carbon::parse($reservation->check_in)->format('d M Y'),
        ]);
    }

    public function handleCheckIn($reservation)
    {
        // Create tasks for Front Desk Agent
        WorkItem::create([
            'title' => 'Prepare guest welcome package',
            'role' => 'Front Desk Agent',
            'priority' => 'High',
            'description' => "Reservation ID: {$reservation->id} - Guest Name: {$reservation->guest_name}",
        ]);
    }

    public function handleRoomIssueReported($issue)
    {
        // Create a situation for Maintenance Staff
        WorkItem::create([
            'title' => 'Room Maintenance Issue',
            'role' => 'Maintenance Staff',
            'priority' => 'High',
            'description' => "Issue: {$issue->description} - Room ID: {$issue->room_id}",
        ]);
    }
}
