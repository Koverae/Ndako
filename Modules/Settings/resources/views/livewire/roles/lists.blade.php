@section('title', "Roles & Permissions")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.role-permission-panel />
@endsection

<section class="w-100">
    <livewire:settings::table.role-permission-table />
</section>
