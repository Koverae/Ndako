
  <div class="order-container d-print-none overflow-y-auto bg-white {{ $interface == 'orders' ? '' : 'd-none' }} h-screen-d">
    <div class="p-6">
      <h2 class="mb-6 text-2xl font-bold text-gray-800">{{ __('Order History') }}</h2>

      <!-- Filters -->
      <div class="flex flex-col gap-4 mb-6 md:flex-row">
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600">{{ __('Status') }}</label>
          <select wire:model.live="orderStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ __('All') }}</option>
            <option value="ongoing">{{ __('Ongoing') }}</option>
            <option value="receipt">{{ __('Completed') }}</option>
            <option value="refunded">{{ __('Refunded') }}</option>
          </select>
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600">{{ __('Payment Status') }}</label>
          <select wire:model.live="paymentStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ __('All') }}</option>
            <option value="unpaid">{{ __('Unpaid') }}</option>
            <option value="paid">{{ __('Paid') }}</option>
          </select>
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600">{{ __('Date Range') }}</label>
          <input type="date" wire:model.live="dateFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="w-full md:w-1/3">
          <label class="text-sm font-medium text-gray-600">{{ __('Search') }}</label>
          <input type="text" wire:model.live="searchOrderQuery" placeholder="{{ __('Search by ID, guest, or table') }}" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      <!-- Loading State -->
      <div wire:loading class="mb-4 text-center text-gray-500" role="status" aria-live="polite">
        <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
        </svg>
        {{ __('Loading orders...') }}
      </div>

      <!-- Orders Table -->
<div class="relative overflow-x-auto">
  <!-- subtle dim on load -->
  <div wire:loading.delay.class="opacity-60" class="transition-opacity duration-200">

    <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
      <thead class="sticky top-0 z-10 bg-gray-100">
        <tr>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Order ID') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Table') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Guest') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Total') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Status') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Payment') }}</th>
          <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Actions') }}</th>
        </tr>
      </thead>

      {{-- Skeleton (smooth, minimal) --}}
      <tbody class="divide-y divide-gray-200" wire:loading.delay wire:target="orderStatusFilter,paymentStatusFilter,dateFilter,searchOrderQuery,selectOrder,cancelOrder,printPreBill,confirmRefund">
        @for($i=0;$i<6;$i++)
          <tr class="animate-pulse">
            @for($c=0;$c<7;$c++)
              <td class="px-4 py-3">
                <div class="h-3.5 w-24 bg-gray-200 rounded"></div>
              </td>
            @endfor
          </tr>
        @endfor
      </tbody>

      {{-- Data --}}
      <tbody class="divide-y divide-gray-200" wire:loading.remove>
        @forelse($orders as $order)
          <tr class="transition-colors duration-150 hover:bg-gray-50" wire:key="order-row-{{ $order->id }}">
            <td class="px-4 py-3 text-sm font-medium text-gray-800">#{{ $order->receipt_number }}</td>
            <td class="px-4 py-3 text-sm">{{ $order->table->table_name ?? 'Direct Sale' }}</td>
            <td class="px-4 py-3 text-sm">{{ $order->guest->name ?? 'N/A' }}</td>
            <td class="px-4 py-3 text-sm">{{ format_currency($order->total_amount + ($order->tax_amount ?? 0)) }}</td>
            <td class="px-4 py-3 text-sm">
              @php
                $status = $order->status;
                $statusClasses = [
                  'ongoing'   => 'bg-amber-100 text-amber-800',
                  'completed' => 'bg-green-100 text-green-800',
                  'receipt'   => 'bg-green-100 text-green-800',
                  'refunded'  => 'bg-red-100 text-red-800',
                  'canceled'  => 'bg-red-100 text-red-800',
                ][$status] ?? 'bg-gray-100 text-gray-800';
              @endphp
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $statusClasses }}">
                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                {{ ucfirst($status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm">
              @php
                $p = $order->payment_status;
                $payClasses = $p === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
              @endphp
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $payClasses }}">
                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                {{ ucfirst($p) }}
              </span>
            </td>

            {{-- Actions (icon-first, labels appear on md+) --}}
            <td class="px-4 py-3 text-sm">
              <div class="flex flex-wrap items-center gap-1.5 md:gap-2">
                @php $cartData = session("pos_cart_{$pos->id}"); @endphp

                {{-- Select --}}
                @if ($order->status === 'ongoing' && ($cartData['active_order_id'] ?? null) != $order->id)
                  <button
                    wire:click="selectOrder('{{ $order->id }}')"
                    class="inline-flex items-center gap-1 btn btn-primary btn-sm transition duration-150 hover:bg-indigo-600"
                    title="{{ __('Select this order') }}"
                  >
                    <i class="bi bi-check2-circle text-base"></i>
                    <span class="d-none d-md-inline">{{ __('Select') }}</span>
                  </button>
                @endif

                {{-- Delete (ongoing only) --}}
                @can('cancel_pos_order')
                  @if($order->status == 'ongoing')
                    <button
                      wire:click="cancelOrder('{{ $order->id }}')"
                      wire:confirm="{{ __('Do you really want to delete this order?') }}"
                      class="inline-flex items-center gap-1 btn btn-danger btn-sm transition duration-150 hover:bg-red-600"
                      title="{{ __('Delete this order') }}"
                    >
                      <i class="bi bi-trash text-base"></i>
                      <span class="d-none d-md-inline">{{ __('Delete') }}</span>
                    </button>
                  @endif
                @endcan

                {{-- Pre-Bill (ongoing only) --}}
                @can('reprint_last_receipt')
                  @if($order->status == 'ongoing')
                    <button
                      wire:click="printPreBill('{{ $order->id }}')"
                      class="inline-flex items-center gap-1 btn btn-info btn-sm transition duration-150 hover:bg-blue-600"
                      title="{{ __('Print pre-bill') }}"
                    >
                      <i class="bi bi-printer text-base"></i>
                      <span class="d-none d-md-inline">{{ __('Pre-Bill') }}</span>
                    </button>
                  @endif
                @endcan

                {{-- Refund (paid & not refunded) --}}
                @can('refund_pos_order')
                @if($order->status != 'refunded' && $order->payment_status == 'paid')
                  <button
                    wire:click="confirmRefund('{{ $order->id }}')"
                    class="inline-flex items-center gap-1 btn btn-danger btn-sm transition duration-150 hover:bg-red-600"
                    title="{{ __('Refund this order') }}"
                  >
                    <i class="bi bi-arrow-counterclockwise text-base"></i>
                    <span class="d-none d-md-inline">{{ __('Refund') }}</span>
                  </button>
                @endif
                @endcan
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-sm text-center text-gray-500">
              {{ __('No orders found.') }}
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


      <!-- Pagination -->
      <div class="mt-4">
        {{-- {{ $orders->links() }} --}}
      </div>

      <!-- Confirmation Modal Placeholder (kept as is, structure-ready) -->
      <div x-data="{ open: false, action: '', orderId: null }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
          <h3 class="mb-4 text-lg font-bold">{{ __('Confirm Action') }}</h3>
          <p class="text-sm text-gray-600" x-text="action === 'delete' ? '{{ __('Are you sure you want to delete this order?') }}' : '{{ __('Are you sure you want to refund this order?') }}'"></p>
          <div class="flex justify-end gap-2 mt-6">
            <button x-on:click="open = false" class="btn btn-secondary btn-sm">{{ __('Cancel') }}</button>
            <button x-on:click="open = false; $wire.dispatch(action, [orderId])" class="btn btn-danger btn-sm">{{ __('Confirm') }}</button>
          </div>
        </div>
      </div>

      <!-- Order Details Modal Placeholder -->
      <div x-data="{ detailsOpen: false }" x-show="detailsOpen" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg">
          <h3 class="mb-4 text-lg font-bold">{{ __('Order Details') }}</h3>
          <div wire:loading wire:target="showOrderDetails" class="text-center text-gray-500">
            <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
            </svg>
            {{ __('Loading details...') }}
          </div>
          <div x-show="!$wire.loading">
            <p class="text-sm text-gray-600">{{ __('Order ID') }}: <span wire:model="selectedOrder.receipt_number"></span></p>
            <p class="text-sm text-gray-600">{{ __('Items') }}: <span wire:model="selectedOrder.items"></span></p>
            <p class="text-sm text-gray-600">{{ __('Total') }}: <span wire:model="selectedOrder.total_amount"></span></p>
          </div>
          <div class="flex justify-end mt-6">
            <button x-on:click="detailsOpen = false" class="btn btn-secondary btn-sm">{{ __('Close') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
