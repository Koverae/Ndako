<?php

namespace Modules\ChannelManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Booking\Booking;

class BookingModal extends ModalComponent 
{
    public $booking;

    public function mount(Booking $booking){
        $this->booking = $booking;
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.booking-modal');
    }
}
