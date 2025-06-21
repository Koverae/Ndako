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

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(){
        $this->guests = Guest::isCompany(current_company()->id)->get();
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.guest-modal');
    }

    #[On('load-guests')]
    public function loadGuests(): void
    {
        $this->guests = Guest::isCompany(current_company()->id)->get();
    }

    public function updatedGuestSearch()
    {
        // Update guests based on guestSearch term
        $this->guests = Guest::isCompany(current_company()->id)
            ->where('name', 'like', '%' . $this->guestSearch . '%')
            ->orWhere('email', 'like', '%' . $this->guestSearch . '%')
            ->orWhere('phone', 'like', '%' . $this->guestSearch . '%')
            ->get();
    }

    #[On('guest-added')]
    public function assignCreatedGuest($guestId)
    {
        // Find the guest by ID
        $guest = Guest::find($guestId);

        // If guest exists, dispatch the event with the guest ID
        if ($guest) {
            $this->dispatch('assign-created-guest', guestId: $guestId);
            $this->dispatch('closeModal');
        } else {
            // Handle case where guest is not found (optional)
            session()->flash('error', 'Guest not found.');
        }
    }

    public function selectGuest($guestId){
        $this->dispatch('assigned-guest', guest: $guestId);
        $this->dispatch('closeModal');
    }
}
