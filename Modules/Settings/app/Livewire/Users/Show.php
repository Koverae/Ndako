<?php

namespace Modules\Settings\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public function mount(User $user){
        $this->user = $user;
    }

    public function render()
    {
        return view('settings::livewire.users.show')->extends('layouts.app');
    }
}
