@section('title', "Maintenance Requests")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.task-panel />
@endsection

<section class="w-100">
    <livewire:settings::table.work-item-table />
</section>
