
  <div class="order-container d-print-none overflow-y-auto bg-white <?php echo e($interface == 'orders' ? '' : 'd-none'); ?> h-screen-d">
    <div class="p-6">
      <h2 class="mb-6 text-2xl font-bold text-gray-800"><?php echo e(__('Order History')); ?></h2>

      <!-- Filters -->
      <div class="flex flex-col gap-4 mb-6 md:flex-row">
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600"><?php echo e(__('Status')); ?></label>
          <select wire:model.live="orderStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            <option value=""><?php echo e(__('All')); ?></option>
            <option value="ongoing"><?php echo e(__('Ongoing')); ?></option>
            <option value="receipt"><?php echo e(__('Completed')); ?></option>
            <option value="refunded"><?php echo e(__('Refunded')); ?></option>
          </select>
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600"><?php echo e(__('Payment Status')); ?></label>
          <select wire:model.live="paymentStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            <option value=""><?php echo e(__('All')); ?></option>
            <option value="unpaid"><?php echo e(__('Unpaid')); ?></option>
            <option value="paid"><?php echo e(__('Paid')); ?></option>
          </select>
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600"><?php echo e(__('Date Range')); ?></label>
          <input type="date" wire:model.live="dateFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600"><?php echo e(__('Search')); ?></label>
          <input type="text" wire:model.live="searchOrderQuery" placeholder="<?php echo e(__('Search by ID, guest, or table')); ?>" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      <!-- Loading State -->
      <div wire:loading class="mb-4 text-center text-gray-500" role="status" aria-live="polite">
        <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
        </svg>
        <?php echo e(__('Loading orders...')); ?>

      </div>

      <!-- Orders Table -->
<div class="relative overflow-x-auto">
  <!-- subtle dim on load -->
  <div wire:loading.delay.class="opacity-60" class="transition-opacity duration-200">

    <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
      <thead class="sticky top-0 z-10 bg-gray-100">
        <tr>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Order ID')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Table')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Guest')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Total')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Status')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Payment')); ?></th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"><?php echo e(__('Actions')); ?></th>
        </tr>
      </thead>

      
      <tbody class="divide-y divide-gray-200" wire:loading.delay wire:target="orderStatusFilter,paymentStatusFilter,dateFilter,searchOrderQuery,selectOrder,cancelOrder,printPreBill,confirmRefund">
        <!--[if BLOCK]><![endif]--><?php for($i=0;$i<6;$i++): ?>
          <tr class="animate-pulse">
            <!--[if BLOCK]><![endif]--><?php for($c=0;$c<7;$c++): ?>
              <td class="px-4 py-3">
                <div class="h-3.5 w-24 bg-gray-200 rounded"></div>
              </td>
            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
          </tr>
        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
      </tbody>

      
      <tbody class="divide-y divide-gray-200" wire:loading.remove>
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr class="transition-colors duration-150 hover:bg-gray-50" wire:key="order-row-<?php echo e($order->id); ?>">
            <td class="px-4 py-3 text-sm font-medium text-gray-800">#<?php echo e($order->receipt_number); ?></td>
            <td class="px-4 py-3 text-sm"><?php echo e($order->table->table_name ?? 'Direct Sale'); ?></td>
            <td class="px-4 py-3 text-sm"><?php echo e($order->guest->name ?? 'N/A'); ?></td>
            <td class="px-4 py-3 text-sm"><?php echo e(format_currency($order->total_amount + ($order->tax_amount ?? 0))); ?></td>
            <td class="px-4 py-3 text-sm">
              <?php
                $status = $order->status;
                $statusClasses = [
                  'ongoing'   => 'bg-amber-100 text-amber-800',
                  'completed' => 'bg-green-100 text-green-800',
                  'receipt'   => 'bg-green-100 text-green-800',
                  'refunded'  => 'bg-red-100 text-red-800',
                  'canceled'  => 'bg-red-100 text-red-800',
                ][$status] ?? 'bg-gray-100 text-gray-800';
              ?>
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 rounded-full <?php echo e($statusClasses); ?>">
                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                <?php echo e(ucfirst($status)); ?>

              </span>
            </td>
            <td class="px-4 py-3 text-sm">
              <?php
                $p = $order->payment_status;
                $payClasses = $p === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
              ?>
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 rounded-full <?php echo e($payClasses); ?>">
                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                <?php echo e(ucfirst($p)); ?>

              </span>
            </td>

            
            <td class="px-4 py-3 text-sm">
              <div class="flex flex-wrap items-center gap-1.5 md:gap-2">
                <?php $cartData = session("pos_cart_{$pos->id}"); ?>

                
                <!--[if BLOCK]><![endif]--><?php if($order->status === 'ongoing' && ($cartData['active_order_id'] ?? null) != $order->id): ?>
                  <button
                    wire:click="selectOrder('<?php echo e($order->id); ?>')"
                    class="inline-flex items-center gap-1 btn btn-primary btn-sm transition duration-150 hover:bg-indigo-600"
                    title="<?php echo e(__('Select this order')); ?>"
                  >
                    <i class="bi bi-check2-circle text-base"></i>
                    <span class="d-none d-md-inline"><?php echo e(__('Select')); ?></span>
                  </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel_pos_order')): ?>
                  <!--[if BLOCK]><![endif]--><?php if($order->status == 'ongoing'): ?>
                    <button
                      wire:click="cancelOrder('<?php echo e($order->id); ?>')"
                      wire:confirm="<?php echo e(__('Do you really want to delete this order?')); ?>"
                      class="inline-flex items-center gap-1 btn btn-danger btn-sm transition duration-150 hover:bg-red-600"
                      title="<?php echo e(__('Delete this order')); ?>"
                    >
                      <i class="bi bi-trash text-base"></i>
                      <span class="d-none d-md-inline"><?php echo e(__('Delete')); ?></span>
                    </button>
                  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reprint_last_receipt')): ?>
                  <!--[if BLOCK]><![endif]--><?php if($order->status == 'ongoing'): ?>
                    <button
                      wire:click="printPreBill('<?php echo e($order->id); ?>')"
                      class="inline-flex items-center gap-1 btn btn-info btn-sm transition duration-150 hover:bg-blue-600"
                      title="<?php echo e(__('Print pre-bill')); ?>"
                    >
                      <i class="bi bi-printer text-base"></i>
                      <span class="d-none d-md-inline"><?php echo e(__('Pre-Bill')); ?></span>
                    </button>
                  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('refund_pos_order')): ?>
                <!--[if BLOCK]><![endif]--><?php if($order->status != 'refunded' && $order->payment_status == 'paid'): ?>
                  <button
                    wire:click="confirmRefund('<?php echo e($order->id); ?>')"
                    class="inline-flex items-center gap-1 btn btn-danger btn-sm transition duration-150 hover:bg-red-600"
                    title="<?php echo e(__('Refund this order')); ?>"
                  >
                    <i class="bi bi-arrow-counterclockwise text-base"></i>
                    <span class="d-none d-md-inline"><?php echo e(__('Refund')); ?></span>
                  </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="7" class="px-4 py-6 text-sm text-center text-gray-500">
              <?php echo e(__('No orders found.')); ?>

            </td>
          </tr>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </tbody>
    </table>
  </div>
</div>


      <!-- Pagination -->
      <div class="mt-4">
        
      </div>

      <!-- Confirmation Modal Placeholder (kept as is, structure-ready) -->
      <div x-data="{ open: false, action: '', orderId: null }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
          <h3 class="mb-4 text-lg font-bold"><?php echo e(__('Confirm Action')); ?></h3>
          <p class="text-sm text-gray-600" x-text="action === 'delete' ? '<?php echo e(__('Are you sure you want to delete this order?')); ?>' : '<?php echo e(__('Are you sure you want to refund this order?')); ?>'"></p>
          <div class="flex justify-end gap-2 mt-6">
            <button x-on:click="open = false" class="btn btn-secondary btn-sm"><?php echo e(__('Cancel')); ?></button>
            <button x-on:click="open = false; $wire.dispatch(action, [orderId])" class="btn btn-danger btn-sm"><?php echo e(__('Confirm')); ?></button>
          </div>
        </div>
      </div>

      <!-- Order Details Modal Placeholder -->
      <div x-data="{ detailsOpen: false }" x-show="detailsOpen" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg">
          <h3 class="mb-4 text-lg font-bold"><?php echo e(__('Order Details')); ?></h3>
          <div wire:loading wire:target="showOrderDetails" class="text-center text-gray-500">
            <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
            </svg>
            <?php echo e(__('Loading details...')); ?>

          </div>
          <div x-show="!$wire.loading">
            <p class="text-sm text-gray-600"><?php echo e(__('Order ID')); ?>: <span wire:model="selectedOrder.receipt_number"></span></p>
            <p class="text-sm text-gray-600"><?php echo e(__('Items')); ?>: <span wire:model="selectedOrder.items"></span></p>
            <p class="text-sm text-gray-600"><?php echo e(__('Total')); ?>: <span wire:model="selectedOrder.total_amount"></span></p>
          </div>
          <div class="flex justify-end mt-6">
            <button x-on:click="detailsOpen = false" class="btn btn-secondary btn-sm"><?php echo e(__('Close')); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/partials/pos/orders.blade.php ENDPATH**/ ?>