@section('title', "Bookings")

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.booking-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:channelmanager::table.booking-table />
</section>
<!-- Page Content -->