@section('title', "Expenses")

<!-- Control Panel -->
@section('control-panel')
<livewire:revenuemanager::navbar.control-panel.expense-panel />
@endsection

<section class="w-100">
    <livewire:revenuemanager::table.expense-table />
</section>
