<?php

namespace Modules\Settings\Livewire\Modal;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Modules\Settings\Models\Language\Language;

class SwitchLanguageModal extends ModalComponent
{
    public $language;

    public function mount(Language $language){
        $this->language = $language;
    }

    public function render()
    {
        return view('settings::livewire.modal.switch-language-modal');
    }

    public function translate(){
        $this->closeModal();
    }
}
