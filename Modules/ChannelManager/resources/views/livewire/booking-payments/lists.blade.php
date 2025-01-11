@section('title', "Payments")

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.booking-payment-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:channelmanager::table.booking-payment-table />
</section>
<!-- Page Content -->