@section('title', 'New')

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.property-type-panel :event="'create-property-type'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.property-type-form />
</section>
<!-- Page Content -->