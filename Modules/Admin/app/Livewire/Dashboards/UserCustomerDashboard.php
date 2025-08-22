<?php

namespace Modules\Admin\Livewire\Dashboards;

use App\Models\Company\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserCustomerDashboard extends Component
{
    public $period = 30;
    public $startDate, $endDate;
    public $companies, $companyGrowth, $companyChangeRate, $users, $userGrowth, $userChangeRate, $activeUsers, $activeUserChangeRate;
    public $recentSignups, $topActiveUsers;
    public $monthlyActiveUsers;

    public function mount($updating = false){

        $this->startDate = Carbon::today()->subDays($this->period)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');

        $this->loadData();
        $this->monthlyActiveUsers = $this->getMonthlyActiveUsers();

    }

    public function loadData(){

        $currentStart = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);


        // === Total Company Growth Rate ===
        $startTotalCompanies = Company::whereDate('created_at', '<', $currentStart)->count();
        $endTotalCompanies = Company::whereDate('created_at', '<=', $endDate)->count();

        $this->companyGrowth = $endTotalCompanies - $startTotalCompanies;

        $this->companyChangeRate = $startTotalCompanies > 0
            ? (($this->companyGrowth) / $startTotalCompanies) * 100
            : null;

        $this->companies = Company::with(['users', 'properties', 'units'])->get();

        // === Total User Growth Rate ===
        $startTotalUsers = User::whereDate('created_at', '<', $currentStart)->count();
        $endTotalUsers = User::whereDate('created_at', '<=', $endDate)->count();

        $this->userGrowth = $endTotalUsers - $startTotalUsers;

        $this->userChangeRate = $startTotalUsers > 0
            ? (($this->userGrowth) / $startTotalUsers) * 100
            : null;

        // === Fetch Users with Role Name ===
        $this->users = User::with('roles')->get()->map(function ($user) {
            $user->role_name = $user->roles->pluck('name')->implode(', ');
            return $user;
        });

        // === Active Users Count ===
        $this->activeUsers = User::whereBetween('last_login_at', [$currentStart, $endDate])->count();

        // === Active User Growth ===
        $startActiveUsers = User::where('last_login_at', '<', $currentStart)->count();
        $endActiveUsers = User::where('last_login_at', '<=', $endDate)->count();

        $this->activeUserChangeRate = $startActiveUsers > 0
            ? (($endActiveUsers - $startActiveUsers) / $startActiveUsers) * 100
            : null;

        // === Recent Signups ===
        $this->recentSignups = Company::withCount('users')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->orderByDesc('created_at')
                    ->limit(30)
                        ->get();

        // === Top Active Users ===
        $this->topActiveUsers = Company::whereHas('users', function ($query) {
                $query->whereBetween('last_login_at', [$this->startDate, $this->endDate]);
            })
            ->withCount(['users as active_user_count' => function ($query) {
                $query->whereBetween('last_login_at', [$this->startDate, $this->endDate]);
            }])
            ->orderByDesc('active_user_count')
                ->limit(30)
                    ->get();
    }

    // Get Monthly Active Users
    public function getMonthlyActiveUsers()
    {
        // Group users by month of their last login in the current year
        $users = User::whereYear('last_login_at', Carbon::now()->year)
            ->selectRaw('
                MONTH(last_login_at) as month,
                YEAR(last_login_at) as year,
                COUNT(DISTINCT id) as active_users
            ')
            ->groupBy('month', 'year')
            ->orderByRaw('YEAR(last_login_at), MONTH(last_login_at)')
            ->get();

        // Format results
        return $users->map(fn ($user) => [
            'month' => Carbon::create($user->year, $user->month, 1)->format('F Y'),
            'active_users' => (int) $user->active_users,
        ]);
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
        return view('admin::livewire.dashboards.user-customer-dashboard');
    }
}
