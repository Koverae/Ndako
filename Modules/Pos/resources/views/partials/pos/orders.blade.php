
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
      <div class="overflow-x-auto">
        <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
          <thead class="bg-gray-100">
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
          <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
              <tr class="transition duration-150 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm">{{ $order->receipt_number }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->table->table_name ?? 'Direct Sale' }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->guest->name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-sm">{{ format_currency($order->total_amount + ($order->tax_amount ?? 0)) }}</td>
                <td class="px-4 py-3 text-sm">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $order->status == 'ongoing' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($order->payment_status) }}
                  </span>
                </td>
                <td class="flex gap-2 px-4 py-3 text-sm">
                  @php $cartData = session("pos_cart_{$pos->id}"); @endphp
                  @if ($order->status === 'ongoing' && ($cartData['active_order_id'] ?? null) != $order->id)
                    <button
                      wire:click="selectOrder('{{ $order->id }}')"
                      class="relative transition duration-150 btn btn-primary btn-sm group hover:bg-indigo-600"
                      title="{{ __('Select this order') }}"
                    >
                      {{ __('Select') }}
                      <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Select this order') }}</span>
                    </button>
                  @endif
                  @if($order->status == 'ongoing')
                    <button
                      wire:click="cancelOrder('{{ $order->id }}')"
                      wire:confirm="{{ __('Do you really want to delete this order?') }}"
                      class="relative transition duration-150 btn btn-danger btn-sm group hover:bg-red-600"
                      title="{{ __('Delete this order') }}"
                    >
                      {{ __('Delete') }}
                      <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Delete this order') }}</span>
                    </button>
                    <button
                      wire:click="printPreBill('{{ $order->id }}')"
                      class="relative transition duration-150 btn btn-info btn-sm group hover:bg-blue-600"
                      title="{{ __('Print pre-bill') }}"
                    >
                      {{ __('Pre-Bill') }}
                      <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Print pre-bill') }}</span>
                    </button>
                  @endif
                  @if($order->status != 'refunded' && $order->payment_status == 'paid')
                    <button
                      wire:click="confirmRefund('{{ $order->id }}')"
                      class="relative transition duration-150 btn btn-danger btn-sm group hover:bg-red-600"
                      title="{{ __('Refund this order') }}"
                    >
                      {{ __('Refund') }}
                      <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Refund this order') }}</span>
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-3 text-sm text-center text-gray-500">{{ __('No orders found.') }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
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