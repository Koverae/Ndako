<?php

namespace Modules\Pos\Livewire\Interface;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Pos\Models\Order\PosOrderDetail;
use Modules\Pos\Models\Pos\Pos;

class KdsBoard extends Component
{
    public ?Pos $pos = null;
    public ?int $posId = null;
    public $lastSeenIds = [];

    public bool $isLocked = false; // for lock screen entangle
    public bool $bootstrapped = false; // Ignore only the very first render
    public string $station = 'kitchen'; // kitchen | bar | pass
    public array $stations = ['kitchen'=>'Kitchen','bar'=>'Bar','pass'=>'Pass'];

    public int $sinceMins = 240; // window
    public bool $soundOn = true;
    /** Play-sound throttle to avoid audio spam (in milliseconds) */
    public int $soundThrottleMs = 900;

    /** Last timestamp we played a sound (ms since epoch) */
    public ?int $lastSoundAtMs = null;

    protected array $allowed = ['queued','preparing','ready','delivered']; //for "new ticket" sound
    protected $casts = [
        'lastSeenIds' => 'array',   // NEW: ensure stable hydration
    ];

    public function mount(?string $station = 'kitchen', ?int $posId = null)
    {
        $this->station = $station ?: 'kitchen';
        $this->pos     = $this->posId ? Pos::isCompany(current_company()->id)->find($this->posId) : null;
        $this->posId   = $posId;

        // basic permission gate (adjust to your guard)
        // if ($this->station === 'bar') {
        //     abort_unless(Auth::user()->can('view_bar_kds'), 403);
        // } else {
        //     abort_unless(Auth::user()->can('view_kds'), 403);
        // }
    }

    public function markPreparing(int $detailId): void
    {
        $d = PosOrderDetail::find($detailId);
        if (!$d) return;
        $d->update([
            'kds_status'       => 'preparing',
            'kds_user_id'      => Auth::id(),
            'kds_preparing_at' => now(),
        ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function markReady(int $detailId): void
    {
        $d = PosOrderDetail::find($detailId);
        if (!$d) return;
        $d->update([
            'kds_status'  => 'ready',
            'kds_user_id' => Auth::id(),
            'kds_ready_at'=> now(),
        ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function bump(int $detailId): void
    {
        $d = PosOrderDetail::find($detailId);
        if (!$d) return;
        $d->update([
            'kds_status'       => 'delivered',
            'kds_user_id'      => Auth::id(),
            'kds_delivered_at' => now(),
        ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function prepareAll(int $orderId): void
    {
        PosOrderDetail::where('pos_order_id',$orderId)
            ->forStation($this->station)
            ->where('kds_status','queued')
            ->update([
                'kds_status'       => 'preparing',
                'kds_user_id'      => Auth::id(),
                'kds_preparing_at' => now(),
            ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function readyAll(int $orderId): void
    {
        PosOrderDetail::where('pos_order_id',$orderId)
            ->forStation($this->station)
            ->whereIn('kds_status',['queued','preparing'])
            ->update([
                'kds_status'  => 'ready',
                'kds_user_id' => Auth::id(),
                'kds_ready_at'=> now(),
            ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function bumpOrder(int $orderId): void
    {
        PosOrderDetail::where('pos_order_id',$orderId)
            ->forStation($this->station)
            ->whereIn('kds_status',['ready'])
            ->update([
                'kds_status'       => 'delivered',
                'kds_user_id'      => Auth::id(),
                'kds_delivered_at' => now(),
            ]);
        $this->notifyKdsUpdate(); // NEW
    }

    public function toggleSound(): void
    {
        $this->soundOn = !$this->soundOn;
    }

    /**
     * Dispatch the standard KDS update event and (optionally) a sound cue.
     * The sound is throttled and respects the user's sound toggle.
     */

    private function notifyKdsUpdate(bool $withSound = true): void
    {
        // Always notify listeners that KDS state changed
        $this->dispatch('kds-updated');

        // Optional sound (guarded + throttled)
        if (!$withSound || !$this->soundOn) {
            return;
        }

        $now = (int) floor(microtime(true) * 1000);
        if ($this->lastSoundAtMs !== null && ($now - $this->lastSoundAtMs) < $this->soundThrottleMs) {
            return; // too soon — skip sound
        }
        $this->lastSoundAtMs = $now;

        // Front-end already listens to 'play-sound' to trigger audio
        $this->dispatch('play-sound', type: 'kds');
    }

    // Defensive: swallow accidental server events (if any client calls Livewire.dispatch('kds-updated'))
    // #[On('kds-updated')]
    // public function _swallowKdsUpdated(): void
    // {
    //     // no-op on purpose
    // }
    #[On('refresh-kds')]
    public function refreshKds(): void
    {
        // Front-end already listens to 'play-sound' to trigger audio
        $this->dispatch('play-sound', type: 'kds');
    }

    /* ----------------------- Drag & Drop actions ----------------------- */


    public function switchStation(string $station): void
    {
        if (!array_key_exists($station, $this->stations)) return;
        $this->station = $station;

        // Reset baseline so switching stations doesn't falsely "ding"
        $this->lastSeenIds = [];
        $this->bootstrapped = false; // NEW
    }

    /* ----------------------- Drag & Drop ----------------------- */

    public function moveItem(int $detailId, string $to): void
    {
        if (!in_array($to, $this->allowed, true)) return;

        $d = PosOrderDetail::query()
            ->whereKey($detailId)
            ->where('kds_station', $this->station)
            ->first();

        if (!$d) return;

        $attrs = ['kds_status' => $to, 'kds_user_id' => Auth::id()];

        match ($to) {
            'queued'    => $attrs += ['kds_preparing_at'=>null,'kds_ready_at'=>null,'kds_delivered_at'=>null],
            'preparing' => $attrs += ['kds_preparing_at'=>now(),'kds_ready_at'=>null,'kds_delivered_at'=>null],
            'ready'     => $attrs += ['kds_ready_at'=>now(),'kds_delivered_at'=>null],
            'delivered' => $attrs += ['kds_delivered_at'=>now()],
            default     => null,
        };

        $d->update($attrs);
        $this->notifyKdsUpdate(); // NEW
    }

    public function moveOrder(int $orderId, string $to): void
    {
        if (!in_array($to, $this->allowed, true)) return;

        $attrs = ['kds_status'=>$to, 'kds_user_id'=>Auth::id()];
        $stamp = now();

        match ($to) {
            'queued'    => $attrs += ['kds_preparing_at'=>null,'kds_ready_at'=>null,'kds_delivered_at'=>null],
            'preparing' => $attrs += ['kds_preparing_at'=>$stamp,'kds_ready_at'=>null,'kds_delivered_at'=>null],
            'ready'     => $attrs += ['kds_ready_at'=>$stamp,'kds_delivered_at'=>null],
            'delivered' => $attrs += ['kds_delivered_at'=>$stamp],
            default     => null,
        };

        PosOrderDetail::query()
            ->where('pos_order_id', $orderId)
            ->where('kds_station', $this->station)
            ->whereIn('kds_status', ['queued','preparing','ready'])
            ->update($attrs);

        $this->notifyKdsUpdate(); // NEW
    }



    /* -------------------------- Data -------------------------- */

    public function getTickets(): array
    {
        $since = Carbon::now()->subMinutes($this->sinceMins);

        $orders = PosOrder::with(['details' => function ($q) {
                $q->where('kds_station', $this->station)
                  ->whereIn('kds_status', ['queued','preparing','ready'])
                  ->with('product');
            }, 'table', 'guest'])
            ->when($this->posId, fn($q) => $q->where('pos_id', $this->posId))
            ->where('status', 'ongoing')
            ->where('date', '>=', $since)
            ->latest('id')
            ->get();

        $buckets = ['queued'=>[], 'preparing'=>[], 'ready'=>[]];

        foreach ($orders as $order) {
            $grouped = ['queued'=>[], 'preparing'=>[], 'ready'=>[]];

            foreach ($order->details as $d) {
                $grouped[$d->kds_status][] = $d;
            }

            foreach ($grouped as $status => $items) {
                if (!$items) continue;
                $buckets[$status][] = (object)[
                    'order' => $order,
                    'items' => $items,
                ];
            }
        }

        return $buckets;
    }

    public function render()
    {
        $data = $this->getTickets(); // ['queued'=>..,'preparing'=>..,'ready'=>..]

        // -------- Detect newly-arrived items in "New" (queued) column --------
        try {
            // 1) Gather queued detail IDs
            $currentQueuedIds = [];
            foreach (($data['queued'] ?? []) as $block) {
                foreach ($block->items as $it) {
                    $currentQueuedIds[] = (int) $it->id;
                }
            }

            // 2) Normalize to avoid false diffs (order/duplicates)
            sort($currentQueuedIds);
            $currentQueuedIds = array_values(array_unique($currentQueuedIds));

            // 3) First render ever → baseline only (no sound)
            if (!$this->bootstrapped) {
                $this->lastSeenIds = $currentQueuedIds;
                $this->bootstrapped = true; // we’re now “live”
            } else {
                // 4) Compute real "new" set
                $prev = $this->lastSeenIds ?: [];
                $newOnes = array_values(array_diff($currentQueuedIds, $prev));

                // 5) Play a sound when:
                //    - there's at least one brand-new ID, OR
                //    - we transitioned from 0 → N after we already bootstrapped
                $isZeroToSome = empty($prev) && !empty($currentQueuedIds);
                if (($isZeroToSome || !empty($newOnes)) && $this->soundOn) {
                    $this->notifyKdsUpdate(); // emits kds-updated + throttled play-sound
                    Log::info('KDS new queued items', [
                        'new_ids' => $newOnes,
                        'prev'    => $prev,
                        'curr'    => $currentQueuedIds,
                    ]);
                }

                // 6) Update baseline
                $this->lastSeenIds = $currentQueuedIds;
            }
        } catch (\Throwable $e) {
            // Be silent on detection errors, but you can log if you want:
            // Log::warning('KDS new detection failed', ['err' => $e->getMessage()]);
        }

        // -------------------------------------------------------------------------------
        

        return view('pos::livewire.interface.kds-board', array_merge($data, [
            'pos' => $this->pos,
        ]))->extends('layouts.pos');
    }


    public function goToBackend()
    {
        return $this->redirect(route('pos.overview'), navigate: true);
    }
}
