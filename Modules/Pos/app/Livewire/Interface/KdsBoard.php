<?php

namespace Modules\Pos\Livewire\Interface;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    public string $station = 'kitchen'; // kitchen | bar | pass
    public array $stations = ['kitchen'=>'Kitchen','bar'=>'Bar','pass'=>'Pass'];

    public int $sinceMins = 240; // window
    public bool $soundOn = true;

    protected array $allowed = ['queued','preparing','ready','delivered']; //for "new ticket" sound

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
            'kds_status'      => 'preparing',
            'kds_user_id'     => Auth::id(),
            'kds_preparing_at'=> now(),
        ]);
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
    }

    public function bump(int $detailId): void
    {
        $d = PosOrderDetail::find($detailId);
        if (!$d) return;
        $d->update([
            'kds_status'     => 'delivered',
            'kds_user_id'    => Auth::id(),
            'kds_delivered_at'=> now(),
        ]);
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
    }

    public function readyAll(int $orderId): void
    {
        PosOrderDetail::where('pos_order_id',$orderId)
            ->forStation($this->station)
            ->whereIn('kds_status',['queued','preparing'])
            ->update([
                'kds_status'   => 'ready',
                'kds_user_id'  => Auth::id(),
                'kds_ready_at' => now(),
            ]);
    }

    public function bumpOrder(int $orderId): void
    {
        PosOrderDetail::where('pos_order_id',$orderId)
            ->forStation($this->station)
            ->whereIn('kds_status',['ready'])
            ->update([
                'kds_status'      => 'delivered',
                'kds_user_id'     => Auth::id(),
                'kds_delivered_at'=> now(),
            ]);
    }

    public function toggleSound(): void
    {
        $this->soundOn = !$this->soundOn;
    }

    /* ----------------------- Drag & Drop actions ----------------------- */


    public function switchStation(string $station): void
    {
        if (!array_key_exists($station, $this->stations)) return;
        $this->station = $station;
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
        $this->dispatch('kds-updated');
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

        $this->dispatch('kds-updated');
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
        return view('pos::livewire.interface.kds-board', array_merge($data, [
            'pos' => $this->pos,
        ]))->extends('layouts.pos');
    }
}
