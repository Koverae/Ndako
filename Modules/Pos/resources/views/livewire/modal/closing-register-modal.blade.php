<div>
    <div class="modal-content rounded-lg shadow-lg border-0">
        <div class="modal-header p-2 d-flex flex-row justify-content-between text-truncate mb-1">
            <h5 class="modal-title font-semibold">{{ __("Closing Register") }}</h5>
            <span class="fw-bolder fs-4">{{ $session->orders()->count() ?? 0 }} {{ __('orders') }}: {{ format_currency($session->orders()->sum('total_amount') ?? 0) }}</span>
        </div>

        <form wire:submit.prevent="open">
            <div class="modal-body p-0">
                <!-- Payment Method Overview -->
                <div class="payment-methods-overview">

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span>{{ __('Balance') }}</span>
                        <span>{{ format_currency(126700) }}</span>
                    </div>
                    
                </div>
                <div class="p-3">
                    <div class="mb-1">
                        <label for="opening_cash" class="form-label fw-bold">{{ __('Opening Cash') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ settings()->currency->symbol ?? '$' }}</span>
                            <input type="number" min="0" step="0.01" wire:model.defer="opening_cash" id="opening_cash" class="form-control" placeholder="0.00" required>
                        </div>
                        @error('opening_cash') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="opening_note" class="form-label fw-bold">{{ __('Opening Note') }}</label>
                        <textarea wire:model.defer="opening_note" id="opening_note" class="form-control" rows="3" placeholder="{{ __('Add a note (optional)') }}"></textarea>
                        @error('opening_note') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer left-0 bg-light rounded-b-lg">
                <button type="submit" class="btn btn-primary fs-3">{{ __('Close Register') }}</button>
                <button type="button" class="btn btn-secondary fs-3" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
            </div>
        </form>
    </div>
</div>
