@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-type-panel :event="'create-unit-type'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.unit-type-form />
</section>
<!-- Page Content -->