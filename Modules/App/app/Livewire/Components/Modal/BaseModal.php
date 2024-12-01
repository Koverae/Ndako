<?php

namespace Modules\App\Livewire\Components\Modal;

use Livewire\Component;

// use LivewireUI\Modal\ModalComponent;

class BaseModal extends Component
{
    public $title = 'Your Modal Title';
    public bool $blocked = false;

    public function render()
    {
        return view('app::livewire.components.modal.base-modal');
    }
    
    public function inputs(){
        return null;
    }
    
    public function buttons(){
        return null;
    }
}
