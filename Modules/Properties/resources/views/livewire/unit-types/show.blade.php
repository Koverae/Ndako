@section('title', $this->type->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-type-panel :type="$type" :event="'update-unit-type'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.unit-type-form :type="$type" />
</section>
<!-- Page Content -->