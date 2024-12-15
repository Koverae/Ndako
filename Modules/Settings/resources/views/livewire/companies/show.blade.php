@section('title', $this->company->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.company-panel :company="$company" :isForm="true" :event="'update-company'" />
@endsection

<section class="">
    <livewire:settings::form.company-form :company="$company" />
</section>
