@section('title', $this->unit->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-panel :unit="$unit" :event="'update-unit'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.unit-form :unit="$unit" />
</section>
<!-- Page Content -->