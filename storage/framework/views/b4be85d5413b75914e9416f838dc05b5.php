<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e($booking->reference); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="p-0 modal-body">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::form.booking-form', ['booking' => $booking]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1655075508-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Close')); ?></button>
        
      </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\ChannelManager\resources\views\livewire\modal\booking-modal.blade.php ENDPATH**/ ?>