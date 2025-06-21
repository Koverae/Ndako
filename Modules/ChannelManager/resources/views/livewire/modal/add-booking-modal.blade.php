<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Add Booking</h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>
        <div class="p-0 modal-body">
            <livewire:channelmanager::wizard.add-booking-wizard :startDate="$startDate" :endDate="$endDate" />
        </div>
        <div class="p-0 modal-footer">
            <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
        </div>
    </div>
</div>
