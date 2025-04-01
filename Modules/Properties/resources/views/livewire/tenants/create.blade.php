@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.tenant-panel :event="'create-tenant'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:properties::form.tenant-form />
</section>
<!-- Page Content -->
