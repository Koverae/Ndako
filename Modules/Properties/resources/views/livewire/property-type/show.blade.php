@section('title', $this->type->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.property-type-panel :type="$type" :event="'update-property-type'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.property-type-form :type="$type" />
</section>
<!-- Page Content -->