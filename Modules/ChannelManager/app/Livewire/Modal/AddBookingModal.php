<?php

namespace Modules\ChannelManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Booking\Booking;

class AddBookingModal extends ModalComponent
{
    public $startDate, $endDate;

    public function mount($startDate, $endDate){
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.add-booking-modal');
    }
}
