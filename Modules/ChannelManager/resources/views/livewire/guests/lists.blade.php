@section('title', "Guests")

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.guest-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:channelmanager::table.guest-table />
</section>
<!-- Page Content -->