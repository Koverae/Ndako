@section('title', "New")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.user-panel :isForm="true" :event="'create-user'" />
@endsection

<section class="page-body">
    <livewire:settings::form.user-form />
</section>