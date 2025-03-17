<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">Add Booking</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="p-0 modal-body">
        {{-- {{ $startDate }} {{ $endDate }} --}}
        <livewire:channelmanager::wizard.add-booking-wizard :startDate="$startDate" :endDate="$endDate" />
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
        {{-- <button class="btn btn-primary" wire:click.prevent="addGuest">{{ __('Add') }}</button> --}}
      </div>
    </div>
</div>
