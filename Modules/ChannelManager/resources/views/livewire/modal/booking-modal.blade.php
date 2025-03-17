<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ $booking->reference }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="p-0 modal-body">
        <livewire:channelmanager::form.booking-form :booking="$booking" />
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
        {{-- <button class="btn btn-primary" wire:click.prevent="addGuest">{{ __('Add') }}</button> --}}
      </div>
    </div>
</div>
