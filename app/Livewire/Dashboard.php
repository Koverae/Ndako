<?php

namespace App\Livewire;

use App\Models\Module\Module;
use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;

class Dashboard extends Component
{
    public $guestsCurrentlyStaying, $checkoutsToday, $bookings;

    public function mount()
    {
        $this->loadData();
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

    public function openApp($module){
        // Retrieve the current array from the cache
        $module = Module::find($module);
        update_menu($module->navbar_id);

        return $this->redirect(route($module->link, ['subdomain' => current_company()->domain_name, 'menu' => current_menu()]), navigate: true);
    }
}