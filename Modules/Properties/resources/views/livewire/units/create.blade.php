@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-panel :event="'create-unit'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::wizard.add-unit-wizard />
</section>
<!-- Page Content -->