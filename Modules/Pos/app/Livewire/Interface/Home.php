<?php
declare(strict_types=1);

namespace Modules\Pos\Livewire\Interface;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Product\Product;
use Modules\Pos\Models\Product\ProductCategory;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Pos\Models\Floor\FloorPlan;
use Modules\Pos\Models\Floor\Table;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Pos\Models\Order\PosOrderDetail;
use Modules\Pos\Models\Order\PosOrderPayment;
use Modules\Pos\Models\Pos\PosSession;

/**
 * POS Front interface (Tables / Register / Orders / Payment)
 *
 * Key goals:
 *  - Keep DB writes safe (tx + guards), reads lean (selects + eager)
 *  - Keep session, cart, and DB state in sync
 *  - Fail loudly to logs, fail softly to UI
 */
class Home extends Component
{
    // ---- Constants ---------------------------------------------------------

    private const SESSION_CART_PREFIX   = 'pos_cart_';
    private const SESSION_ID_PREFIX     = 'pos_session_id_';
    private const TAX_RATE              = 0.16; // NOTE: consider making this hotel/company-configurable
    private const ORDERS_PAGE_SIZE      = 50;

    // ---- Public state (Livewire) ------------------------------------------

    public Pos $pos;

    public string $interface = 'tables';
    public string $tab = 'pay';
    public string $calculatorMode = 'qty';
    public string $toPrint = 'receipt';

    public ?int $selectedCategoryId = null;
    public ?int $selectedProductId  = null;
    public ?int $selectedPlanId     = null;
    public ?int $selectedCustomerId = null;

    /** @var Collection<int, ProductCategory> */
    public Collection $productCategoryOptions;

    /** @var Collection<int, Product> */
    public Collection $productOptions;

    /** @var Collection<int, PosOrder> */
    public Collection $orders;

    /** @var Collection<int, Guest> */
    public Collection $customers;

    public ?PosOrder $order = null;
    public ?Guest    $guest = null;

    /** @var Collection<int, FloorPlan>|null */
    public $floorPlanOptions = null;

    public ?Table $selectedTable = null;

    /** Cart is keyed by productId */
    public array $cart = [];

    /** Current service selection (eat-in/take-away/in-room) */
    public array $selectedService = [];

    /** Service menu */
    public array $services = [
        'eat-in'     => ['key'=> 'eat-in',     'label' => 'Eat-In',         'icon' => 'fas fa-utensils'],
        'take-away'  => ['key'=> 'take-away',  'label' => 'Take-Away',      'icon' => 'bi bi-bag-fill'],
        'in-room'    => ['key'=> 'in-room',    'label' => 'In-Room Service','icon' => 'bi bi-door-closed-fill'],
    ];

    /** Totals (computed) */
    public float $cartTotal = 0.0;
    public float $cartTax   = 0.0;

    public string $searchQuery = '';
    public string $customerSearch = '';
    public ?string $orderNote = '';

    public string $orderStatusFilter = '';
    public string $paymentStatusFilter = '';

    public bool $isLocked = false;

    public ?string $dateFilter = null;
    public string $searchOrderQuery = '';

    public bool $onHold = false;
    public bool $rush   = false;

    public ?float $serviceCharge = null;
    public ?float $tipAmount     = null;

    /** Calculator input (string to allow empty) */
    public string $calculatorInput = '';


    // ---- Lifecycle ---------------------------------------------------------

    /**
     * Mount component with initial state from session and DB.
     */
    public function mount(Pos $pos): void
    {
        $this->pos = $pos;

        // POS lock if no active session or session not in browser session bag
        if (!session()->has($this->sessionIdKey()) || !$this->pos->active_session_id) {
            $this->isLocked = true;
        }

        // Restore per-POS UI session
        $sessionData = session()->get($this->cartSessionKey(), [
            'cart' => [],
            'table_id' => null,
            'active_order_id' => null,
        ]);

        // Repair malformed session structures gracefully
        if (!is_array($sessionData) ||
            !array_key_exists('cart', $sessionData) ||
            !array_key_exists('table_id', $sessionData) ||
            !array_key_exists('active_order_id', $sessionData)) {
            $sessionData = ['cart' => [], 'table_id' => null, 'active_order_id' => null];
            session()->put($this->cartSessionKey(), $sessionData);
        }

        $this->cart = (array) ($sessionData['cart'] ?? []);
        $this->selectedTable = isset($sessionData['table_id'])
            ? Table::query()
                ->where('company_id', current_company()->id)
                ->find($sessionData['table_id'])
            : null;

        $this->order = isset($sessionData['active_order_id'])
            ? PosOrder::query()
                ->with(['details.product'])
                ->where('company_id', current_company()->id)
                ->find($sessionData['active_order_id'])
            : null;

        if ($this->order) {
            $this->syncCartWithOrder(); // Ensures cart matches DB, avoids stale lines
        }

        $this->dateFilter = Carbon::today()->format('Y-m-d');

        // Initial loads (lean selects + scopes)
        $this->loadFloors();
        $this->loadCategories();
        $this->loadProducts();
        $this->loadOrders();
        $this->loadCustomers();

        $this->recalculateTotals();
        $this->loadActiveOrder();
    }

    // ---- UI state changes --------------------------------------------------

    public function changeTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function changeInterface(string $interface): void
    {
        $this->interface = $interface;

        if ($interface === 'orders') {
            $this->loadOrders();
        } elseif ($interface === 'tables') {
            $this->loadActiveOrder();
        }
    }

    public function changeFloorPlan(?int $floorPlanId): void
    {
        $this->selectedPlanId = $floorPlanId;
    }

    public function selectCategory(?int $categoryId): void
    {
        if($categoryId === 0) {
            $categoryId = null;
        }
        $this->selectedCategoryId = $categoryId ?: null;
        $this->loadProducts();
    }

    // ---- Notes -------------------------------------------------------------

    /**
     * Persist order note to session and DB (if an order is active).
     */
    public function updatedOrderNote($val): void
    {
        session(["pos_order_note_{$this->pos->id}" => $val]);

        if ($this->order) {
            PosOrder::whereKey($this->order->id)->update(['note' => (string) $val]);
            $this->dispatch('refresh-kds'); // e.g., to refresh KDS
        }
    }

    /**
     * Save Customer/Kitchen note (visible on KDS).
     * - Writes to `customer_note` if present, otherwise tries `note` or `kitchen_note`.
     * - Emits a small flash the UI already listens for.
     */
    public function saveOrderNote(): void
    {
        $this->updatedOrderNote($this->orderNote);
        session()->flash('note_saved', __('Note saved'));
    }

    // ---- Tables & Orders ---------------------------------------------------

    /**
     * Assign an order to a table; create order if needed.
     */
    public function selectTable(int $tableId): void
    {
        $table = Table::query()
            ->where('company_id', current_company()->id)
            ->find($tableId);

        if (!$table) {
            $this->toastError('Table not found!', 'Selected table does not exist.');
            return;
        }

        // Existing ongoing order for that table?
        $this->order = PosOrder::query()
            ->with(['details.product'])
            ->where('company_id', current_company()->id)
            ->where('pos_id', $this->pos->id)
            ->where('table_id', $tableId)
            ->where('status', 'ongoing')
            ->first();

        $this->selectedTable = $table;
        $this->selectServiceType('eat-in');

        if ($this->order) {
            $this->syncCartWithOrder();
            $this->selectedCustomerId = $this->order->customer_id;
        } elseif (!empty($this->cart)) {
            $this->createOrder();
        }

        $table->update(['status' => 'occupied']);
        $this->interface = 'register';
        $this->saveCartToSession();

        $this->toastSuccess('Table assigned!', "Order assigned to {$table->table_name}");
    }

    /**
     * Select an order from the Orders list.
     */
    public function selectOrder(int $orderId): void
    {
        $order = PosOrder::query()
            ->with(['details.product'])
            ->where('company_id', current_company()->id)
            ->find($orderId);

        if (!$order) {
            $this->toastError('Order not found!', 'Selected order does not exist.');
            return;
        }

        $this->order = $order;
        $this->selectedTable = $order->table_id
            ? Table::query()
                ->where('company_id', current_company()->id)
                ->find($order->table_id)
            : null;

        $this->selectedCustomerId = $order->customer_id;
        $this->syncCartWithOrder();
        $this->interface = 'register';
        $this->saveCartToSession();

        $this->toastSuccess('Order selected!', 'Order is now active.');
    }

    /**
     * Release a table and clear active state if it was selected.
     */
    public function releaseTable(int $tableId): void
    {
        $table = Table::query()
            ->where('company_id', current_company()->id)
            ->find($tableId);

        if ($table && $table->status !== 'available') {
            $table->update(['status' => 'available']);

            if ($this->selectedTable?->id === $tableId) {
                $this->selectedTable = null;
                $this->order = null;
                $this->cart = [];
                $this->recalculateTotals();
                $this->saveCartToSession();
            }

            $this->toastSuccess('Table released!', "Table {$table->table_name} is now available.");
        }
    }

    // ---- Cart --------------------------------------------------------------

    /**
     * Select or deselect a product for calculator editing.
     */
    public function selectProduct(int $productId): void
    {
        if ($this->selectedProductId === $productId) {
            $this->selectedProductId = null;
            $this->calculatorInput = '';
            return;
        }

        $this->selectedProductId = $productId;
        $item = $this->cart[$productId] ?? null;

        if ($item) {
            $this->calculatorInput = match ($this->calculatorMode) {
                'qty'      => (string) $item['quantity'],
                'price'    => (string) $item['unit_price'],
                'discount' => (string) $item['discount'],
                default    => '',
            };
        } else {
            $this->calculatorInput = '';
        }
    }

    /**
     * Save cart snapshot to session.
     */
    protected function saveCartToSession(): void
    {
        session()->put($this->cartSessionKey(), [
            'cart'            => $this->cart,
            'table_id'        => $this->selectedTable?->id,
            'active_order_id' => $this->order?->id,
        ]);
    }

    /**
     * Add a product line to cart and persist to DB (create order if needed).
     */
    public function addToCart(int $productId): void
    {
        $product = Product::query()
            ->select(['id', 'product_name', 'product_price', 'product_category_id'])
            ->where('company_id', current_company()->id)
            ->find($productId);

        if (!$product) {
            $this->toastError('Product not found!', 'Product selected does not exist.');
            return;
        }

        if (!$this->order) {
            $this->createOrder();
        }

        try {
            DB::transaction(function () use ($product) {
                if (isset($this->cart[$product->id])) {
                    // Increment quantity
                    $this->cart[$product->id]['quantity']++;

                    PosOrderDetail::query()
                        ->where('pos_order_id', $this->order->id)
                        ->where('product_id', $product->id)
                        ->update([
                            'quantity'  => $this->cart[$product->id]['quantity'],
                            'sub_total' => $this->cart[$product->id]['quantity'] * $this->cart[$product->id]['unit_price'],
                        ]);
                } else {
                    // New line
                    $this->cart[$product->id] = [
                        'id'         => $product->id,
                        'name'       => $product->product_name,
                        'unit_price' => (float) $product->product_price,
                        'quantity'   => 1,
                        'discount'   => 0, // NOTE: interpret as % or amount consistently later
                    ];

                    PosOrderDetail::create([
                        'pos_order_id'            => $this->order->id,
                        'company_id'              => current_company()->id,
                        'product_id'              => $product->id,
                        'quantity'                => 1,
                        'unit_price'              => (float) $product->product_price,
                        'sub_total'               => (float) $product->product_price,
                        'product_discount_amount' => 0,
                        // 'kds_station'             => optional($product->category)->kds_station ?? 'kitchen',
                        // 'kds_status'              => 'queued',
                    ]);
                }

                if ($this->selectedTable) {
                    $this->selectedTable->update(['status' => 'occupied']);
                }

                $this->recalculateTotals();
                $this->persistOrderTotals();
                $this->saveCartToSession();
            });
        } catch (Throwable $e) {
            Log::error('POS addToCart failed', [
                'pos_id' => $this->pos->id,
                'company_id' => current_company()->id,
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not add product', 'Please try again.');
            return;
        }

        $this->toastSuccess('Product added!', 'Product added to cart');
        $this->dispatch('play-sound', type: 'beep');
    }

    /**
     * Remove a product line from cart and DB.
     */
    public function removeFromCart(int $productId): void
    {
        if (!isset($this->cart[$productId]) || !$this->order) {
            return;
        }

        try {
            DB::transaction(function () use ($productId) {
                unset($this->cart[$productId]);

                PosOrderDetail::query()
                    ->where('pos_order_id', $this->order->id)
                    ->where('product_id', $productId)
                    ->delete();

                if (empty($this->cart)) {
                    // No lines left: remove the order
                    $this->order->delete();
                    $this->order = null;
                } else {
                    $this->recalculateTotals();
                    $this->persistOrderTotals();
                }

                $this->saveCartToSession();
            });
        } catch (Throwable $e) {
            Log::error('POS removeFromCart failed', [
                'pos_id' => $this->pos->id,
                'company_id' => current_company()->id,
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not remove product', 'Please try again.');
            return;
        }

        $this->toastSuccess('Product removed!', 'Product removed from cart');
    }

    /**
     * Update quantity (min 1) and persist.
     */
    public function updateQuantity(int $productId, $quantity): void
    {
        if (!$this->order || !isset($this->cart[$productId])) {
            return;
        }

        $qty = max(1, (int) $quantity);

        try {
            DB::transaction(function () use ($productId, $qty) {
                $this->cart[$productId]['quantity'] = $qty;

                PosOrderDetail::query()
                    ->where('pos_order_id', $this->order->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $qty, 'sub_total' => $qty * $this->cart[$productId]['unit_price']]);

                $this->recalculateTotals();
                $this->persistOrderTotals();
                $this->saveCartToSession();
            });
        } catch (Throwable $e) {
            Log::error('POS updateQuantity failed', [
                'pos_id' => $this->pos->id,
                'company_id' => current_company()->id,
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not update quantity', 'Please try again.');
        }
    }

    /**
     * Cancel & delete an order (restores inventory).
     */
    public function cancelOrder(int $orderId): void
    {
        if (!$this->order || $this->order->id !== $orderId) {
            $this->order = PosOrder::query()
                ->with('details.product')
                ->where('company_id', current_company()->id)
                ->find($orderId);
        }

        if (!$this->order) {
            $this->toastError('Order not found!', 'Selected order does not exist.');
            return;
        }

        try {
            DB::transaction(function () {
                // Restore inventory per line
                foreach ($this->order->details as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('product_quantity', $detail->quantity);
                    }
                }

                $this->order->details()->delete();
                $this->order->delete();

                $this->order = null;
            });
        } catch (Throwable $e) {
            Log::error('POS cancelOrder failed', [
                'pos_id' => $this->pos->id,
                'order_id' => $orderId,
                'company_id' => current_company()->id,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not delete order', 'Please try again.');
            return;
        }

        $this->resetCart();
        $this->interface = 'tables';
        $this->toastSuccess('Order deleted!', 'The order has been deleted.');
    }

    /**
     * Reset the whole cart & UI selection.
     */
    public function resetCart(): void
    {
        $this->cart = [];
        $this->selectedTable = null;
        $this->selectedCustomerId = null;
        $this->guest = null;

        $this->recalculateTotals();
        $this->saveCartToSession();

        $this->toastSuccess('Cart reset!', 'Cart has been cleared.');
    }


    /**
     * Recalculate tip amount whenever the cart or tip percent changes.
     * Call this after cart updates or when setTipPercent() is used.
     */
    protected function recalcTip(): void
    {
        $base = (float) ($this->cartTotal ?? 0);
        $this->tipAmount = round($base * ((float)$this->tipPercent / 100), 2);
    }

    /**
     * Persist arbitrary, safe meta fields to the order if column(s) exist.
     * Falls back silently if the column is not present in your schema.
     */
    protected function persistOrderMeta(array $attrs): void
    {
        $order = $this->order;
        if (!$order) return;

        // Only set attributes that actually exist on the model
        $fillable = method_exists($order, 'getFillable') ? $order->getFillable() : [];
        $toSet    = array_intersect_key($attrs, array_flip($fillable));

        if (!empty($toSet)) {
            $order->fill($toSet)->save();
        }
    }

    // ---- Totals ------------------------------------------------------------

    public function getTotalProperty(): float
    {
        // NOTE: Currently ignores 'discount' in cart. Align with DB discount semantics before changing.
        return collect($this->cart)
            ->sum(fn ($item) => (float) $item['unit_price'] * (int) $item['quantity']);
    }

    public function getTaxProperty(): float
    {
        return $this->cartTotal * self::TAX_RATE;
    }

    protected function recalculateTotals(): void
    {
        $this->cartTotal = $this->getTotalProperty();
        $this->cartTax   = $this->getTaxProperty();
    }

    protected function persistOrderTotals(): void
    {
        if ($this->order) {
            $this->order->update([
                'total_amount' => $this->cartTotal,
                'due_amount'   => $this->cartTotal, // NOTE: adjust if you later subtract paid/discounts/tips here
            ]);
        }
    }

    // ---- Loads (lean + scoped) --------------------------------------------

    protected function loadFloors(): void
    {
        $this->floorPlanOptions = FloorPlan::isCompany(current_company()->id)
            ->select(['id', 'name'])
            ->get();

        $this->selectedPlanId = $this->floorPlanOptions->first()->id ?? null;
    }

    protected function loadCategories(): void
    {
        $this->productCategoryOptions = ProductCategory::isCompany(current_company()->id)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    public function updatedSearchQuery($value): void
    {
        $this->loadProducts();
    }

    protected function loadProducts(): void
    {
        $query = Product::query()
            ->select(['id', 'product_name', 'product_price', 'product_category_id', 'image_path'])
            ->where('company_id', current_company()->id);

        if ($this->selectedCategoryId) {
            $query->where('product_category_id', $this->selectedCategoryId);
        }

        if ($this->searchQuery !== '') {
            $q = trim($this->searchQuery);
            $query->where('product_name', 'like', "%{$q}%");
        }

        $this->productOptions = $query->orderBy('product_name')->get();
    }

    public function loadOrders(): void
    {
        $query = PosOrder::query()
            ->with([
                'table:id,table_name',
                'guest:id,name',
            ])
            ->where('pos_id', $this->pos->id)
            ->where('company_id', current_company()->id);

        if ($this->orderStatusFilter) {
            $query->where('status', $this->orderStatusFilter);
        }

        if ($this->paymentStatusFilter) {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        if ($this->dateFilter) {
            $date = Carbon::parse($this->dateFilter)->toDateString();
            $query->whereDate('date', $date);
        }

        if ($this->searchOrderQuery) {
            $needle = trim($this->searchOrderQuery);
            $query->where(function ($q) use ($needle) {
                $q->where('receipt_number', 'like', "%{$needle}%")
                  ->orWhereHas('table', fn ($t) => $t->where('table_name', 'like', "%{$needle}%"))
                  ->orWhereHas('guest', fn ($g) => $g->where('name', 'like', "%{$needle}%"));
            });
        }

        $this->orders = $query->latest('id')->take(self::ORDERS_PAGE_SIZE)->get();
    }

    public function updatedPaymentStatusFilter($value): void { $this->loadOrders(); }
    public function updatedOrderStatusFilter($value): void   { $this->loadOrders(); }
    public function updatedSearchOrderQuery($value): void    { $this->loadOrders(); }
    public function updatedDateFilter($value): void          { $this->loadOrders(); }

    protected function loadCustomers(): void
    {
        $this->customers = Guest::query()
            ->select(['id', 'name', 'phone'])
            ->where('company_id', current_company()->id)
            ->when($this->customerSearch !== '', fn ($q) =>
                $q->where('name', 'like', '%' . trim($this->customerSearch) . '%')
            )
            ->orderBy('name')
            ->take(10)
            ->get();
    }

    // ---- Calculator --------------------------------------------------------

    public function selectCalculatorMode(string $mode): void
    {
        $this->calculatorMode = $mode;

        if ($this->selectedProductId && isset($this->cart[$this->selectedProductId])) {
            $this->calculatorInput = match ($mode) {
                'qty'      => (string) $this->cart[$this->selectedProductId]['quantity'],
                'price'    => (string) $this->cart[$this->selectedProductId]['unit_price'],
                'discount' => (string) $this->cart[$this->selectedProductId]['discount'],
                default    => '',
            };
        } else {
            $this->calculatorInput = '';
        }
    }

    /**
     * Apply calculator input to the selected cart line.
     */
    public function applyCalculatorInput(): void
    {
        $pid = $this->selectedProductId;

        if (!$pid || !isset($this->cart[$pid]) || !$this->order) {
            $this->toastError('No product selected!', 'Please select a product.');
            return;
        }

        $raw = $this->calculatorInput;

        if ($raw !== '' && !is_numeric($raw)) {
            $this->toastError('Invalid input!', 'The value entered is invalid.');
            return;
        }

        try {
            DB::transaction(function () use ($pid, $raw) {
                switch ($this->calculatorMode) {
                    case 'qty':
                        $qty = (int) $raw;
                        if ($qty < 1) {
                            $this->removeFromCart($pid);
                            $this->selectedProductId = null;
                            $this->calculatorInput = '';
                            return;
                        }

                        $this->cart[$pid]['quantity'] = $qty;

                        PosOrderDetail::query()
                            ->where('pos_order_id', $this->order->id)
                            ->where('product_id', $pid)
                            ->update([
                                'quantity'  => $qty,
                                'sub_total' => $qty * $this->cart[$pid]['unit_price'],
                            ]);
                        break;

                    case 'price':
                        $price = max(0, (float) $raw);
                        $this->cart[$pid]['unit_price'] = $price;

                        $qty = (int) $this->cart[$pid]['quantity'];

                        PosOrderDetail::query()
                            ->where('pos_order_id', $this->order->id)
                            ->where('product_id', $pid)
                            ->update([
                                'unit_price' => $price,
                                'sub_total'  => $qty * $price,
                            ]);
                        break;

                    case 'discount':
                        $discount = max(0, (float) $raw);
                        $maxDiscount = Auth::user()?->hasPermissionTo('apply_high_discount') ? 100 : 50;
                        if ($discount > $maxDiscount) {
                            $this->toastError('Discount limit exceeded!', "Discount cannot exceed {$maxDiscount}%.");
                            return;
                        }
                        $this->cart[$pid]['discount'] = $discount;

                        // NOTE: DB column is "product_discount_amount" – clarify if % or absolute; here we mirror as-is.
                        PosOrderDetail::query()
                            ->where('pos_order_id', $this->order->id)
                            ->where('product_id', $pid)
                            ->update(['product_discount_amount' => $discount]);
                        break;
                }

                $this->recalculateTotals();
                $this->persistOrderTotals();
                $this->saveCartToSession();
            });
        } catch (Throwable $e) {
            Log::error('POS applyCalculatorInput failed', [
                'pos_id' => $this->pos->id,
                'company_id' => current_company()->id,
                'product_id' => $pid,
                'mode' => $this->calculatorMode,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not apply change', 'Please try again.');
        }
    }

    // ---- Payment -----------------------------------------------------------

    #[On('processPayment')]
    public function processPayment(): void
    {
        $this->selectedProductId = null;

        if (!$this->order || empty($this->cart)) {
            $this->toastError('No order to process!', 'Please add products to the cart before processing payment.');
            return;
        }

        if (!$this->guest && !$this->selectedCustomerId) {
            $this->toastError('No guest selected!', 'Please select a guest before processing payment.');
            return;
        }

        // Recompute totals just before taking payment
        $this->recalculateTotals();
        $this->persistOrderTotals();
        $this->saveCartToSession();

        $this->toPrint = 'receipt';

        $this->dispatch('openModal', component: 'pos::modal.payment-modal', arguments: ['order' => $this->order->id]);
    }

    #[On('posOrderPaymentCompleted')]
    public function posPaymentCompleted(array $data): void
    {
        $orderId = (int) ($data['orderId'] ?? 0);
        $reference = (string) ($data['reference'] ?? '');
        $amount = (float) ($data['amount'] ?? 0.0);
        $method = (string) ($data['method'] ?? 'paystack');

        $this->order = PosOrder::query()
            ->where('company_id', current_company()->id)
            ->find($orderId);

        if (!$this->order) {
            $this->toastError('Order not found!', 'Payment cannot be recorded.');
            return;
        }

        $ok = $this->handlePayment([
            'orderId'  => $this->order->id,
            'reference'=> $reference,
            'amount'   => $amount,
            'method'   => $method,
        ]);

        if (!$ok) {
            return; // handlePayment already alerted/logged
        }

        $this->resetCart();
        $this->interface = 'payment';
        $this->toPrint = 'receipt';

        $this->toastSuccess('Order completed!', 'Order has been processed successfully.');
        $this->dispatch('play-sound', type: 'cashier');
    }

    /**
     * Create payment + update order balances atomically.
     *
     * @param  array{orderId:int, method:string, amount:float, reference?:string} $data
     * @return bool
     */
    public function handlePayment(array $data): bool
    {
        if (!isset($data['orderId']) || !$this->order || $this->order->id !== (int) $data['orderId']) {
            session()->flash('error', 'Invalid order ID.');
            return false;
        }

        try {
            DB::transaction(function () use ($data) {
                $payment = PosOrderPayment::create([
                    'company_id'     => current_company()->id,
                    'pos_id'         => $this->order->pos_id,
                    'pos_order_id'   => $this->order->id,
                    'pos_session_id' => $this->order->pos_session_id ?? null,
                    'guest_id'       => $this->order->guest_id ?? null,
                    'payment_method' => (string) $data['method'],
                    'amount'         => (float) ($data['amount'] ?? 0),
                    'date'           => now(),
                    'transaction_id' => (string) ($data['reference'] ?? Str::upper(Str::random(16))),
                    'label'          => 'Payment Received for Order #' . $this->order->receipt_number,
                ]);

                // Recalculate due & status
                $due = max(0, (float) $this->order->due_amount - (float) $payment->amount);
                $paid = (float) $this->order->paid_amount + (float) $payment->amount;

                $paymentStatus = $due <= 0 ? 'paid' : ($due < $this->order->total_amount ? 'partial' : 'unpaid');

                $this->order->update([
                    'status'         => 'receipt',
                    'payment_method' => (string) $data['method'],
                    'payment_status' => $paymentStatus,
                    'paid_amount'    => $paid,
                    'due_amount'     => $due,
                ]);
            });
        } catch (Throwable $e) {
            Log::error('POS handlePayment failed', [
                'pos_id' => $this->pos->id,
                'order_id' => $this->order->id ?? null,
                'company_id' => current_company()->id,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Payment failed', 'Could not record payment. Please try again.');
            return false;
        }

        return true;
    }

    // ---- Misc actions ------------------------------------------------------

    #[On('switchInterface')]
    public function switchInterface(string $interface): void
    {
        $this->changeInterface($interface);
    }

    public function unlock(): void
    {
        $this->isLocked = false;
        $this->dispatch('reset-inactivity-timer');
        $this->toastSuccess('Unlocked!', 'POS is now active.');
    }

    public function newOrder(): void
    {
        $this->resetCart();
        $this->interface = 'tables';
        $this->selectedTable = null;
        $this->selectedCustomerId = null;
        $this->orderNote = '';
        $this->toastSuccess('New order started!', 'Ready to create a new order.');
    }

    #[On('assigned-guest')]
    public function assignGuest(int $guestId): void
    {
        $this->selectedCustomerId = $guestId;
        $this->guest = Guest::query()
            ->where('company_id', current_company()->id)
            ->find($guestId);

        if ($this->order) {
            $this->order->update(['customer_id' => $this->selectedCustomerId]);
        }

        if ($this->guest) {
            $this->toastSuccess('New guest selected!', "{$this->guest->name} has been selected!");
        }
    }

    #[On('assign-created-guest')]
    public function assignCreatedGuest(int $guestId): void
    {
        $this->assignGuest($guestId);
    }

    public function deleteOrder(int $orderId): void
    {
        $order = PosOrder::query()
            ->where('company_id', current_company()->id)
            ->find($orderId);

        if (!$order) {
            $this->toastError('Order not found!', 'Selected order does not exist.');
            return;
        }

        try {
            DB::transaction(function () use ($order) {
                $receipt = $order->receipt_number;
                $order->details()->delete();
                $order->delete();

                $this->toastSuccess('Order has been deleted!', "Order {$receipt} has been deleted");
                $this->loadOrders();
            });
        } catch (Throwable $e) {
            Log::error('POS deleteOrder failed', [
                'pos_id' => $this->pos->id,
                'order_id' => $orderId,
                'company_id' => current_company()->id,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not delete order', 'Please try again.');
        }
    }

    public function printPreBill(int $orderId): void
    {
        $order = PosOrder::query()
            ->with(['details.product'])
            ->where('company_id', current_company()->id)
            ->find($orderId);

        if (!$order) {
            $this->toastError('Order not found!', 'Selected order does not exist.');
            return;
        }

        $this->order = $order;
        $this->selectedTable = $order->table_id
            ? Table::query()->where('company_id', current_company()->id)->find($order->table_id)
            : null;

        $this->selectedCustomerId = $order->customer_id;
        $this->syncCartWithOrder();
        $this->saveCartToSession();
        $this->toPrint = 'bill';

        $this->toastSuccess('Bill printing has been launched!', 'Bill printing has been launched.');
        $this->dispatch('print-bill');
    }

    protected function loadActiveOrder(): void
    {
        $this->order = PosOrder::query()
            ->with(['details.product'])
            ->where('pos_id', $this->pos->id)
            ->where('company_id', current_company()->id)
            ->where('status', 'ongoing')
            ->when($this->selectedTable, fn ($q) => $q->where('table_id', $this->selectedTable->id))
            ->when(!$this->selectedTable, fn ($q) => $q->whereNull('table_id'))
            ->first();

        if ($this->order && !empty($this->cart)) {
            $this->syncCartWithOrder();
        }
    }

    /**
     * Hydrate cart from DB order details to avoid drift.
     */
    protected function syncCartWithOrder(): void
    {
        if (!$this->order) {
            return;
        }

        $this->cart = [];

        foreach ($this->order->details as $detail) {
            $this->cart[$detail->product_id] = [
                'id'         => $detail->product_id,
                'name'       => $detail->product?->product_name ?? ('#' . $detail->product_id),
                'unit_price' => (float) $detail->unit_price,
                'quantity'   => (int) $detail->quantity,
                'discount'   => (float) $detail->product_discount_amount,
            ];
        }

        $this->recalculateTotals();
        $this->saveCartToSession();
    }

    /**
     * Create an ongoing order bound to POS/session/table/customer.
     */
    protected function createOrder(): void
    {
        if ($this->order) {
            return;
        }

        try {
            $this->order = PosOrder::create([
                'pos_id'         => $this->pos->id,
                'pos_session_id' => session($this->sessionIdKey()),
                'company_id'     => current_company()->id,
                'cashier_id'     => Auth::id(),
                'table_id'       => $this->selectedTable?->id,
                'customer_id'    => $this->selectedCustomerId,
                'total_amount'   => $this->cartTotal,
                'due_amount'     => $this->cartTotal,
                'status'         => 'ongoing',
                'receipt_number' => $this->generateReceiptNumber(),
                'date'           => now(),
                'service_type'   => $this->selectedService['key'] ?? 'eat-in',
                'note'           => (string) ($this->orderNote ?? ''),
            ]);

            if ($this->selectedTable) {
                $this->selectedTable->update(['status' => 'occupied']);
            }

            $this->saveCartToSession();
        } catch (Throwable $e) {
            Log::error('POS createOrder failed', [
                'pos_id' => $this->pos->id,
                'company_id' => current_company()->id,
                'error' => $e->getMessage(),
            ]);
            $this->toastError('Could not start order', 'Please try again.');
        }
    }

    #[On('selectServiceType')]
    public function selectServiceType(string $service): void
    {
        if (!isset($this->services[$service])) {
            return;
        }

        $this->selectedService = $this->services[$service];

        if ($this->order) {
            $this->order->update(['service_type' => $this->selectedService['key']]);
        }
    }



    /**
     * Receipt share options (email / SMS / WhatsApp etc.)
     */
    public function openReceiptOptions(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.receipt-options', arguments: ['orderId' => $orderId]);
    }

    /**
     * Toggle order hold/resume.
     * - Persists to `on_hold` if your orders table has the column.
     * - Falls back to component state only when column missing.
     */
    public function toggleHold(): void
    {
        $this->onHold = !$this->onHold;

        $order = $this->order;
        if ($order && in_array('on_hold', $order->getFillable() ?? [], true)) {
            $order->on_hold = $this->onHold;
            $order->save();
        }
    }

    /**
     * Send all current items to KDS as "queued".
     * Requires `send_to_kitchen` permission in your policies/guards.
     */
    public function sendOrderToKds(): void
    {
        $order = $this->order;

        if (! $order) {
            return; // stop if no order is set
        }

        // If you have policies/permissions, check here (pseudo):
        // $this->authorize('send_to_kitchen');

      PosOrderDetail::where('pos_order_id', $order->id)
        ->whereNull('kds_status')
            ->update([
                'kds_status'       => 'queued',
                'kds_station'      => 'kitchen',
                'kds_user_id'      => Auth::id(),
                'kds_preparing_at' => null,
                'kds_ready_at'     => null,
                'kds_delivered_at' => null,
            ]);

        $this->dispatch('refresh-kds');

        $this->toastSuccess('Order sent to kitchen!', 'All items have been sent to KDS.');

    }

    /**
     * Fire a specific course to KDS (e.g., starters, mains, desserts).
     * You can adapt the selection criteria to your schema (tags/modifiers).
     */
    public function fireCourse(string $course): void
    {
        $order = $this->order;
        if (!$order) return;

        $course = strtolower($course);
        if (!in_array($course, ['starters','mains','desserts'], true)) return;

        // Example: if you store course on detail row (adjust to your schema)
      PosOrderDetail::query()
            ->where('pos_order_id', $order->id)
            ->where('course', $course)
            ->whereIn('kds_status', [null, '', 'queued']) // not yet in prep/ready
            ->update([
                'kds_status'       => 'queued',
                'kds_station'      => 'kitchen',
                'kds_user_id'      => Auth::id(),
                'kds_preparing_at' => null,
                'kds_ready_at'     => null,
                'kds_delivered_at' => null,
            ]);

        $this->dispatch('refresh-kds');
    }

    /**
     * Toggle “Rush” (high priority) flag for the order.
     * If the DB has an `is_rush` column, persist it.
     */
    public function toggleRush(): void
    {
        $this->rush = !$this->rush;

        $order = $this->order;
        if ($order && in_array('is_rush', $order->getFillable() ?? [], true)) {
            $order->is_rush = $this->rush;
            $order->save();
        }

        // Optionally ping KDS to visually highlight
        $this->dispatch('refresh-kds');
    }

    /**
     * Open a lightweight “schedule fire” modal to program tickets at a time.
     * Implement the scheduling in that modal (cron/queue or delayed job).
     */
    public function openFireSchedule(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.fire-schedule', arguments: ['orderId' => $orderId]);
    }

    /**
     * Open split-bill workflow (choose items, create child bills).
     */
    public function openSplitBill(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.split-bill', arguments: ['orderId' => $orderId]);
    }

    /**
     * Print Kitchen Order Ticket (KOT) on demand.
     * If you already generate KOT server-side, call that here instead.
     */
    public function printKitchenTicket(): void
    {
        // Example browser trigger; replace with your KOT print pipeline
        $this->dispatch('print-kot');
    }

    /**
     * Open “transfer order” (to another waiter) modal.
     */
    public function openTransferOrder(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.transfer-order', arguments: ['orderId' => $orderId]);
    }

    /**
     * Open “move table” modal.
     */
    public function openMoveTable(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.move-table', arguments: ['orderId' => $orderId]);
    }

    /**
     * Open “merge bills” modal.
     */
    public function openMergeBills(): void
    {
        $orderId = $this->order?->id;
        $this->dispatch('openModal', component: 'pos::modal.merge-bills', arguments: ['orderId' => $orderId]);
    }


    /**
     * Whenever the cart changes (items/qty/price), recompute the tip.
     * If your property is named differently, adjust the method name:
     * Livewire will call updated{Name}() automatically.
     */
    public function updatedCart(): void
    {
        $this->recalcTip();
    }

    /**
     * Also recompute if the raw cart total is recalculated elsewhere.
     * Call this helper from your existing methods that change totals.
     */
    protected function afterCartRecalculation(): void
    {
        $this->recalcTip();
    }

    public function goToBackend()
    {
        return $this->redirect(route('pos.overview'), navigate: true);
    }

    public function closeRegister(): void
    {
        $this->dispatch('openModal', component: 'pos::modal.closing-register-modal', arguments: ['pos' => $this->pos, 'session' => $this->pos->active_session_id]);
    }

    public function openRegister(): void
    {
        $this->dispatch('openModal', component: 'pos::modal.opening-control-modal', arguments: ['pos' => $this->pos]);
    }

    public function continueSelling(): void
    {
        $existingSession = PosSession::find($this->pos->active_session_id);

        if ($existingSession) {
            session()->put($this->sessionIdKey(), $existingSession->id);
        } else {
            $this->toastError('No active session found!', 'Please open a session to continue selling.');
            return;
        }

        $this->isLocked = false;
        $this->dispatch('reset-inactivity-timer');

        $this->toastSuccess("POS {$this->pos->name} is now open.", "POS {$this->pos->name} is now open. You can start processing orders.");
    }

    #[On('posOpened')]
    public function posOpened($data): void
    {
        $this->isLocked = false;
        $this->dispatch('reset-inactivity-timer');
        $this->toastSuccess("POS {$this->pos->name} is now open.", "POS {$this->pos->name} is now open. You can start processing orders.");
    }

    public function posClosed($data): void
    {
        $this->isLocked = true;
        $this->dispatch('reset-inactivity-timer');
        $this->toastSuccess("POS {$this->pos->name} is now closed.", "POS {$this->pos->name} is now closed. You can no longer process orders.");
    }

    #[On('posClosed')]
    public function closePos($data)
    {
        // Optional: prevent close if ongoing orders exist
        // $ongoing = PosOrder::query()
        //     ->where('pos_id', $this->pos->id)
        //     ->where('company_id', current_company()->id)
        //     ->where('status', 'ongoing')
        //     ->exists();
        // if ($ongoing) { ...return; }

        $session = PosSession::find($this->pos->active_session_id);
        if (!$session) {
            $this->toastError('No active session!', 'No active session found for this POS.');
            return;
        }

        // Clear browser session for this POS
        session()->forget($this->cartSessionKey());
        session()->forget($this->sessionIdKey());

        // Deactivate POS
        $this->pos->update([
            'active_session_id' => null,
            'status'            => 'inactive',
        ]);

        // Reset component state
        $this->resetCart();
        $this->isLocked = true;

        $this->toastSuccess('POS closed successfully!', "POS {$this->pos->name} has been closed.");
        return $this->redirect(route('pos.overview'), navigate: true);
    }

    // ---- Render ------------------------------------------------------------

    public function render()
    {
        return view('pos::livewire.interface.home')->extends('layouts.pos');
    }

    // ---- Private helpers ---------------------------------------------------

    private function cartSessionKey(): string
    {
        return self::SESSION_CART_PREFIX . $this->pos->id;
    }

    private function sessionIdKey(): string
    {
        return self::SESSION_ID_PREFIX . $this->pos->id;
    }

    private function generateReceiptNumber(): string
    {
        // Example: ORD-24-00012345 (yy + random)
        return 'ORD-' . now()->format('y') . '-' . str_pad((string) random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function toastSuccess(string $title, string $text): void
    {
        LivewireAlert::title($title)
            ->text($text)
            ->success()
            ->position('top-end')
            ->timer(4000)
            ->toast()
            ->show();
    }

    private function toastError(string $title, string $text): void
    {
        LivewireAlert::title($title)
            ->text($text)
            ->error()
            ->position('top-end')
            ->timer(4000)
            ->toast()
            ->show();
    }
}
