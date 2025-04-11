@section('title', $this->tenant->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.tenant-panel :tenant="$tenant" :event="'update-tenant'"  :isForm="true" />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:properties::form.tenant-form :tenant="$this->tenant" />
</section>
<!-- Page Content -->
