@section('title', 'New')

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.booking-panel :event="'create-booking'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:channelmanager::wizard.add-booking-wizard />
</section>
<!-- Page Content -->
