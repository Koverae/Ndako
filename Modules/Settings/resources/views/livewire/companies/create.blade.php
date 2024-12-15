@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.company-panel :isForm="true" :event="'create-company'" />
@endsection

<section class="page-body">
    <livewire:settings::form.company-form />
</section>
