<div>
    <div class="border-0 rounded-lg shadow-lg modal-content">
        <div class="modal-header">
            <h5 class="font-semibold modal-title">{{ __("Opening Control") }}</h5>
            <button type="button" class="btn-close" wire:click="$dispatch('closeModal')" aria-label="Close"></button>
        </div>

        <form wire:submit.prevent="open">
            <div class="pt-4 pb-2 modal-body">
                <div class="mb-4">
                    <label for="opening_cash" class="form-label fw-bold">{{ __('Opening Cash') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ settings()->currency->symbol ?? '$' }}</span>
                        <input type="number" min="0" step="0.01" wire:model.defer="opening_cash" id="opening_cash" class="form-control" placeholder="0.00" required>
                    </div>
                    @error('opening_cash') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="opening_note" class="form-label fw-bold">{{ __('Opening Note') }}</label>
                    <textarea wire:model.defer="opening_note" id="opening_note" class="form-control" rows="3" placeholder="{{ __('Add a note (optional)') }}"></textarea>
                    @error('opening_note') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="rounded-b-lg modal-footer bg-light">
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Open Register') }}</button>
            </div>
        </form>
    </div>
</div>
