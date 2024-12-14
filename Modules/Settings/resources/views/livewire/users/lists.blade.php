@section('title', "Users")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.control-panel.user-panel />
@endsection

<section class="w-100">
    <livewire:settings::table.user-table />
</section>