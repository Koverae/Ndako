<div>
    <div class="modal-content rounded-lg shadow-lg border-0">
        <div class="modal-header p-2 d-flex flex-row justify-content-between text-truncate mb-1">
            <h5 class="modal-title font-semibold">{{ __("Closing Register") }}</h5>
            <span class="fw-bolder fs-4">{{ $session->orders()->count() ?? 0 }} {{ __('orders') }}: {{ format_currency($session->orders()->sum('total_amount') ?? 0) }}</span>
        </div>

        <form wire:submit.prevent="open">
            <div class="modal-body p-0">
                <!-- Payment Method Overview -->
                <div class="payment-methods-overview cursor-pointer p-3">

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span class="fs-3 fw-bold">{{ __('Cash') }}</span>
                        <span class="fs-3">{{ format_currency($totalCash) }}</span>
                    </div>
                    <div class="pl-2 mb-2">
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted">{{ __('Opening') }}</span>
                            <span class="fs-4 text-muted">{{ format_currency($session->starting_balance ?? 0) }}</span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted">{{ __('Payments in Cash') }}</span>
                            <span class="fs-4 text-muted">{{ format_currency($totalPaymentCash ?? 0) }}</span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted"><i class="fas fa-caret-right"></i> {{ __('Cash In/Out') }}</span>
                            <span class="fs-4 text-muted">{{ format_currency($cashInOut) }}</span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted">{{ __('Counted') }}</span>
                            <span class="fs-4 text-muted">{{ format_currency($closing_cash) }}</span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted">{{ __('Difference') }}</span>
                            <span class="fs-4
                                @if(is_null($differenceCash))
                                    text-muted
                                @elseif($differenceCash > 0)
                                    text-success
                                @elseif($differenceCash % 2 !== 0)
                                    text-danger
                                @else
                                    text-muted
                                @endif
                            ">
                               @if($differenceCash > 0)
                                <span>+</span>
                                @elseif($differenceCash < 0)
                                <span>-</span>
                               @endif
                               {{ format_currency($differenceCash) }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex flex-row justify-content-between text-truncate mb-2">
                        <span class="fs-3 fw-bold">{{ __('Card') }}</span>
                        <span class="fs-3">{{ format_currency($totalCard ?? 0) }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span class="fs-3 fw-bold">{{ __('Paystack') }}</span>
                        <span class="fs-3">{{ format_currency($totalPaystack ?? 0) }}</span>
                    </div>

                </div>
                <div class="p-3">
                    <div class="mb-1">
                        <label for="closing_cash" class="form-label fw-bold">{{ __('Closing Cash') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ settings()->currency->symbol ?? '$' }}</span>
                            <input type="number" min="0" step="0.01" wire:model.live="closing_cash" id="closing_cash" class="form-control" placeholder="0.00" required>
                        </div>
                        @error('closing_cash') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="closing_note" class="form-label fw-bold">{{ __('Closing Note') }}</label>
                        <textarea wire:model.defer="closing_note" id="closing_note" class="form-control" rows="3" placeholder="{{ __('Add a note (optional)') }}"></textarea>
                        @error('closing_note') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer left-0 bg-light rounded-b-lg d-flex justify-content-between align-items-center">
                <div>
                    <button type="submit" class="btn btn-primary fs-3">{{ __('Close Register') }}</button>
                    <button type="button" class="btn btn-secondary fs-3" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
                </div>
                <button type="button" class="btn btn-secondary fs-3 gap-3 d-flex" wire:click="showDailySales" wire:loading.attr="disabled"><i class="fas fa-download"></i> {{ __('Daily Sales') }}</button>
            </div>
        </form>
        {{-- <div wire:loading wire:target="open" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.7); z-index: 10;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </div>
        </div> --}}
    </div>
</div>
