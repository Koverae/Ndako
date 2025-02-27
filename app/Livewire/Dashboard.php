<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Settings\Models\WorkItem;
use Modules\Settings\Notifications\MultiChannelNotification;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    public $tasks = [], $situations = [];
    public $guestsCurrentlyStaying, $checkoutsToday, $bookings;

    public function mount()
    {
        $this->loadData();

        $this->tasks = WorkItem::isCompany(current_company()->id)->isTasks()
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->get();

        $this->situations = WorkItem::isCompany(current_company()->id)->isSituations()
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->where('reported_by', auth()->user()->id)
            ->get();
    }

    public function render()
    {

        return view('livewire.dashboard')
            ->extends('layouts.app');
    }

    public function loadData()
    {
        $today = Carbon::today();

        // Guests currently staying in the hotel
        $this->guestsCurrentlyStaying = Booking::whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->whereIn('status', ['checked-in', 'confirmed'])
            ->count();

        // Guests checking out today
        $this->checkoutsToday = Booking::whereDate('check_out', $today)
            ->where('status', 'checked-in')
            ->count();

        $this->bookings = Booking::with(['guest'])
            ->where('check_out', '>=', $today) // Include only current or future bookings
            ->orderBy('check_in', 'asc')
            ->get();
    }


    public function testNotif(){
        $message = "Your booking #ND-2025-01-0004 has been confirmed!";
        $title = "Booking Confirmed";
        User::find(1)->notify(new MultiChannelNotification($message, $title));
        $this->dispatch('fetch-notifications');
    }

    #[On('load-work-items')]
    public function loadWorkItems(){

        $this->tasks = WorkItem::isCompany(current_company()->id)->isTasks()
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->get();

        $this->situations = WorkItem::isCompany(current_company()->id)->isSituations()
            ->where('reported_by', auth()->user()->id)
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->get();
    }
}
