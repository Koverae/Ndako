<?php

namespace Modules\ChannelManager\Livewire\Modal;

use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Livewire\WithFileUploads;

class GuestModal extends ModalComponent
{

    public string $guestSearch = '';
    public $guests;

    public function mount(){
        $this->loadGuests();
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.guest-modal');
    }

    #[On('load-guests')]
    protected function loadGuests(): void
    {
        $this->guests = Guest::isCompany(current_company()->id)
            ->where('name', 'like', '%' . $this->guestSearch . '%')
            ->orWhere('email', 'like', '%' . $this->guestSearch . '%')
            ->take(10)
            ->get();
    }

    public function selectGuest($guestId){
        $this->dispatch('assigned-guest', guest: $guestId);
        $this->dispatch('closeModal');
    }
}
