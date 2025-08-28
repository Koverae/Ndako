@section('title', $this->guest->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.guest-panel :guest="$guest" :event="'update-guest'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:channelmanager::guest-form :guest="$guest" />
</section>
<!-- Page Content -->
