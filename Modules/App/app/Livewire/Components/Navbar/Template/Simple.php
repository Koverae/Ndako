<?php

namespace Modules\App\Livewire\Components\Navbar\Template;

use Livewire\Attributes\On;
use Livewire\Component;

abstract class Simple extends Component
{
    public $currentPage;
    public $save;
    public $change = false;

    public function render()
    {
        return view('app::livewire.components.navbar.template.simple');
    }

    public function saveUpdate(){
        $this->dispatch('save');
    }

    public function cancelUpdate(){
        $this->dispatch('cancel');
    }

    #[On('change')]
    public function changeDetected(){
        $this->change = true;
    }

    #[On('undo-change')]
    public function changeSaved(){
        $this->change = false;
    }
}
