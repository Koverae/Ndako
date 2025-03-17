@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.property-panel :event="'create-property'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::wizard.add-property-wizard />
</section>
<!-- Page Content -->