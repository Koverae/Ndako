@section('title', "Add Expense")

<!-- Control Panel -->
@section('control-panel')
<livewire:revenuemanager::navbar.control-panel.expense-panel :isForm="true"/>
@endsection

<section class="w-100">
    <livewire:revenuemanager::form.expense-form />
</section>
