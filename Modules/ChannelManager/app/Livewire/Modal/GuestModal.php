<?php
declare(strict_types=1);

namespace Modules\ChannelManager\Livewire\Modal;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Throwable;

/**
 * Modal to search/select/create Guests and return the selection to parent components.
 *
 * Events in/out:
 *  - IN  : 'load-guests' (refresh list with current search)
 *  - IN  : 'guest-added' (payload: guestId) -> selects newly created guest
 *  - OUT : 'assigned-guest' (payload: guestId) to parent (existing guest picked)
 *  - OUT : 'assign-created-guest' (payload: guestId) to parent (new guest created)
 */
final class GuestModal extends ModalComponent
{
    /** How many guests to fetch per refresh */
    private const PAGE_SIZE = 25;

    /** Columns we need in the UI */
    private const SELECT_FIELDS = ['id', 'name', 'email', 'phone'];

    /** Search term bound to input */
    public string $guestSearch = '';

    /** @var Collection<int, Guest> */
    public Collection $guests;

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    /**
     * Initial load (recent guests).
     */
    public function mount(): void
    {
        $this->guests = collect();
        $this->refreshGuests();
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.guest-modal');
    }

    // ────────────────────── Events ──────────────────────

    #[On('load-guests')]
    public function loadGuests(): void
    {
        $this->refreshGuests();
    }

    /**
     * A guest was created in another modal → select it here and close.
     */
    #[On('guest-added')]
    public function assignCreatedGuest(int $guestId): void
    {
        $guest = $this->findCompanyGuest($guestId);

        if (!$guest) {
            session()->flash('error', __('Guest not found.'));
            return;
        }

        // Bubble up to parent
        $this->dispatch('assign-created-guest', guestId: $guest->id);
        $this->dispatch('closeModal');
    }

    // ────────────────────── UI bindings ──────────────────────

    /**
     * Whenever the search changes, update the list.
     * (Debounce on the input side in Blade: wire:model.debounce.300ms)
     */
    public function updatedGuestSearch(): void
    {
        $this->guestSearch = trim($this->guestSearch);
        $this->refreshGuests();
    }

    /**
     * User picked an existing guest from the list.
     */
    public function selectGuest(int $guestId): void
    {
        $guest = $this->findCompanyGuest($guestId);

        if (!$guest) {
            session()->flash('error', __('Guest not found.'));
            return;
        }

        $this->dispatch('assigned-guest', guestId: $guest->id);
        $this->dispatch('closeModal');
    }

    // ────────────────────── Data access ──────────────────────

    /**
     * Refresh the guests collection based on the current search term.
     * - Short terms (< 2) → show recent guests
     * - Longer terms      → search name/email/phone, scoped to company
     */
    private function refreshGuests(): void
    {
        try {
            $term = $this->guestSearch;

            $query = Guest::query()
                ->isCompany(current_company()->id)
                ->select(self::SELECT_FIELDS);

            if ($term !== '' && mb_strlen($term) >= 2) {
                $like = '%' . $term . '%';

                // Wrap search ors to preserve the company scope
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                      ->orWhere('email', 'like', $like)
                      ->orWhere('phone', 'like', $like);
                });
            }

            $this->guests = $query
                ->orderBy('name')
                ->limit(self::PAGE_SIZE)
                ->get();

        } catch (Throwable $e) {
            Log::error('GuestModal refreshGuests failed', [
                'company_id' => current_company()->id ?? null,
                'term'       => $this->guestSearch,
                'error'      => $e->getMessage(),
            ]);
            $this->guests = collect();
            session()->flash('error', __('Unable to load guests. Please try again.'));
        }
    }

    /**
     * Fetch a single guest ensuring it belongs to the current company.
     */
    private function findCompanyGuest(int $guestId): ?Guest
    {
        try {
            return Guest::query()
                ->isCompany(current_company()->id)
                ->select(self::SELECT_FIELDS)
                ->find($guestId);
        } catch (Throwable $e) {
            Log::error('GuestModal findCompanyGuest failed', [
                'company_id' => current_company()->id ?? null,
                'guest_id'   => $guestId,
                'error'      => $e->getMessage(),
            ]);
            return null;
        }
    }
}
