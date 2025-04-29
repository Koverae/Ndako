@section('title', "Expense Categories")

<!-- Control Panel -->
@section('control-panel')
<livewire:revenuemanager::navbar.control-panel.expense-category-panel />
@endsection

<section class="w-100">
    <livewire:revenuemanager::table.expense-category-table />
</section>
