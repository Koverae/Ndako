@section('title', "Channels")

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.channel-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:channelmanager::table.channel-table />
</section>
<!-- Page Content -->