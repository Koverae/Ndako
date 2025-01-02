@section('title', "Properties")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.property-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:properties::table.property-table />
</section>
<!-- Page Content -->