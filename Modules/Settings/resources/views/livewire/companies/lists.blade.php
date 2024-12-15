@section('title', "Companies")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.company-panel />
@endsection

<section class="w-100">
    <livewire:settings::table.company-table />
</section>