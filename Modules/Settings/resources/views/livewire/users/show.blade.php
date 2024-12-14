@section('title', $this->user->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.user-panel :user="$user" :isForm="true" :event="'update-user'" />
@endsection

<section class="">
    <livewire:settings::form.user-form :user="$user" />
</section>