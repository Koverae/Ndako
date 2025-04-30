<?php

namespace Modules\RevenueManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
use Modules\ChannelManager\Models\Booking\BookingPayment;

class Expense extends Component
{
    public $period = 1, $property;
    public $properties, $units, $unitTypes, $mothlyInvoices;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = current_property()->id ?? null;
    }

    public function render()
    {
        return view('revenuemanager::livewire.dashboards.expense');
    }
}
