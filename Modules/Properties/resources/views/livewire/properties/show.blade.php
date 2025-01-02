@section('title', $this->property->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.property-panel :property="$property" :event="'update-property'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.property-form :property="$property" />
</section>
<!-- Page Content -->