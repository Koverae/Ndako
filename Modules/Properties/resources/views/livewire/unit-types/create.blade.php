@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-type-panel :isForm="true" :event="'create-unit-type'"  />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::wizard.add-unit-wizard />
</section>
<!-- Page Content -->