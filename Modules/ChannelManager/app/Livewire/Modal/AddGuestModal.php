<?php

namespace Modules\ChannelManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Livewire\WithFileUploads;

class AddGuestModal extends ModalComponent
{
    use WithFileUploads;

    public Guest $guest;

    public $name, $email, $phone, $gender, $birthday, $address, $job, $photo, $image_path;
    
    // Define validation rules
    protected $rules = [
        'name' => 'required|string|max:30',
        'phone' => 'nullable|string|', 
        'email' => 'required|email|unique:guests,email',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'address' => 'nullable|string|', 
        'job' => 'nullable|string|', 
        'birthday' => 'nullable|date|', 
        'gender' => 'required|string', 
    ];

    public function mount($guest = null){
        if($guest){
            $this->name = $guest->name;
            $this->email = $guest->email;
            $this->phone = $guest->phone;
            $this->gender = $guest->gender;
            $this->birthday = $guest->birthday;
            $this->address = $guest->address;
            $this->job = $guest->job;
        }
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.add-guest-modal');
    }

    public function addGuest(){
        $this->validate();
        $guest = Guest::create([
            'company_id' => current_company()->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'street' => $this->address,
            'job' => $this->job,
        ]);
        $guest->save();
        
        $avatar = $guest->id.'_guest.png';
        if($this->photo){
            $this->photo->storeAs('avatars', $avatar, 'public');
        }
        $guest->update([
            'avatar' => $avatar,
        ]);

        $this->dispatch('load-guests');

        $this->closeModal();
    }
}
