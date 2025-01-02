@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.unit-panel :event="'create-unit'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:properties::form.unit-form />
</section>
<!-- Page Content -->