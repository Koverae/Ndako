@section('title', $this->booking->reference)

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.booking-panel :booking="$booking" :event="'update-booking'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:channelmanager::form.booking-form :booking="$booking" />
</section>
<!-- Page Content -->
