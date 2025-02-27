<?php

namespace Modules\Settings\Livewire\Dashboards;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Settings\Models\WorkItem;
use Modules\Settings\Notifications\MultiChannelNotification;
use Livewire\Attributes\On;

class HomeDashboard extends Component
{
    public $tasks, $situations = [];
    public $guestsCurrentlyStaying, $checkinsToday, $checkoutsToday, $bookings;
    public $tasksThisDay, $tasksAssigned, $tasksCompleted, $avgCompletionTime;

    public function mount()
    {
        $this->loadData();

        $this->situations = WorkItem::isCompany(current_company()->id)->isSituations()
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->where('reported_by', auth()->user()->id)
            ->get();
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
        // Guests checking in today
        $this->checkinsToday = Booking::whereDate('check_in', $today)
            ->where('status', 'checked-in')
            ->count();

        $this->bookings = Booking::with(['guest'])
            ->where('check_out', '>=', $today) // Include only current or future bookings
            ->orderBy('check_in', 'asc')
            ->get();


        // Fetch real data

        $this->tasks = WorkItem::isCompany(current_company()->id)->isTasks()
            ->where('assigned_to', auth()->user()->id)
            ->orWhere('assigned_to', null)
            ->isActive()
            ->get();
        $this->tasksThisDay = WorkItem::isCompany(current_company()->id)->isTasks()->whereDate('created_at', today())->count();
        $this->tasksAssigned = WorkItem::isCompany(current_company()->id)->isTasks()->where('status', 'assigned')->count();
        $this->tasksCompleted = WorkItem::isCompany(current_company()->id)->isTasks()->where('status', 'completed')->count();

        // Calculate average completion time
        $completionTimes = WorkItem::isCompany(current_company()->id)->isTasks()->where('status', 'completed')
            ->selectRaw('TIMESTAMPDIFF(HOUR, created_at, updated_at) as duration')
            ->pluck('duration');

        $this->avgCompletionTime = $completionTimes->count()
            ? round($completionTimes->avg(), 1) . ' hours/task'
            : 'N/A';
    }


    public function render()
    {
        return view('settings::livewire.dashboards.home-dashboard');
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
