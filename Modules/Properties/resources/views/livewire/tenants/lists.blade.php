@section('title', "Tenants")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.tenant-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:properties::table.tenant-table />
</section>
<!-- Page Content -->
