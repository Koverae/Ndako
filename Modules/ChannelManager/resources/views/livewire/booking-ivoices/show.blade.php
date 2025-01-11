@section('title', $this->invoice->reference)

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.booking-invoice-panel :invoice="$invoice" :event="'update-invoice'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:channelmanager::form.booking-invoice-form :invoice="$invoice" />
</section>
<!-- Page Content -->
