<?php

namespace Modules\Pos\Livewire\Modal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSession;

class ClosingRegisterModal extends ModalComponent
{
    public Pos $pos;
    public PosSession $session;
    public $opening_cash = 0;
    public $opening_note = '';
    
    public function mount(Pos $pos, PosSession $session)
    {
        $this->pos = $pos;
        $this->session = $session;
        if (!$this->pos) {
            abort(404, 'POS or session not found');
        }
    }

    public function render()
    {
        return view('pos::livewire.modal.closing-register-modal');
    }
}
