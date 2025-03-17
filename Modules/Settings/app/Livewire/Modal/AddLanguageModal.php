<?php

namespace Modules\Settings\Livewire\Modal;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Modules\Settings\Models\Language\Language;

class AddLanguageModal extends ModalComponent
{
    public $language;
    public function render()
    {
        $languages = Language::notInstalled()->get();
        return view('settings::livewire.modal.add-language-modal', compact('languages'));
    }
    

    public function addLanguage(Language $language){
        $this->validate([
            'language' => ['required', 'exists:languages,id']
        ]);

        $language = Language::find($this->language)->first();
        $language->update([
            'is_active' => 1,
        ]);
        $language->save();
        // $this->closeModal();
    }
}
