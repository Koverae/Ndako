<?php

namespace Modules\Pos\Livewire\Modal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\Pos\Models\Pos\Pos;

class OpeningControlModal extends ModalComponent
{
    public Pos $pos;
    public $opening_cash = 0;
    public $opening_note = '';

    public function mount(Pos $pos)
    {
        $this->pos = $pos;
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function open()
    {
        $this->validate([
            'opening_cash' => 'required|numeric|min:0',
            'opening_note' => 'nullable|string|max:255',
            // 'opening_date' => 'required|date',
        ]);

        // Check if there is already an active session for this POS
        $existingSession = $this->pos->sessions()->isPosActive()->first();

        if ($existingSession) {
            // Use the existing active session
            $session = $existingSession;
        } else {
            // Create a new session for this POS
            $session = $this->pos->sessions()->create([
                'company_id' => $this->pos->company_id,
                'status' => 'active',
                'starting_balance' => $this->opening_cash,
                'start_date' => now(),
                'open_by_id' => Auth::user()->id, // Track who opened the session
            ]);
        }

        // Persist the session ID in the user's session for this POS
        session()->put("pos_session_id_{$this->pos->id}", $session->id);

        // Mark the POS as open
        $this->pos->update([
                'active_session_id' => session("pos_session_id_{$this->pos->id}"),
                'status' => 'active'
            ]);

        // Close the modal and notify listeners
        $this->closeModal();
        $this->dispatch('posOpened', [
            'pos' => $this->pos,
            'session' => $session,
        ]);
    }

    public function render()
    {
        return view('pos::livewire.modal.opening-control-modal');
    }
}
