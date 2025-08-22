<?php

namespace Modules\Admin\Livewire\Dashboards;

use App\Models\Team\Team;
use Carbon\Carbon;
use Livewire\Component;

class RevenueBillingDashboard extends Component
{
    public $period = 30;
    public $startDate, $endDate;
    public $payingUsers, $arpu, $fromStarterToSparkConversions, $churnRate, $bestPlan;

    public function mount($updating = false){

        $this->startDate = Carbon::today()->subDays($this->period)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');

        $this->loadData();

    }

    public function loadData(){

        $currentStart = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);

        // === Total Paying Customers ===
        $this->payingUsers = Team::whereHas('subscribed.plan', function ($query) {
                $query->where('is_free', false); // Only paid plans
            })
            ->with(['subscribed.plan']) // Optional: eager load plan details
            ->get();

    }

    public function updatedStartDate($property){

        if (Carbon::parse($this->startDate)->gt($this->endDate)) {
            // Start date is after end date
            session()->flash('error', 'Start date must be before end date.');
        } else {
            $this->loadData();
        }

    }

    public function updatedEndDate($property){

        if (Carbon::parse($this->startDate)->gt($this->endDate)) {
            // Start date is after end date
            session()->flash('error', 'Start date must be before end date.');
        } else {
            $this->loadData();
        }
    }

    public function render()
    {
        return view('admin::livewire.dashboards.revenue-billing-dashboard');
    }
}
