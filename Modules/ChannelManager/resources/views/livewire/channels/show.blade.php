@section('title', $this->channel->name)

<!-- Control Panel -->
@section('control-panel')
<livewire:channelmanager::navbar.control-panel.channel-panel :channel="$channel" :event="'update-channel'" :isForm="true" />
@endsection
<!-- Page Content -->
<section class="">
    <livewire:channelmanager::form.channel-form :channel="$channel" />
</section>
<!-- Page Content -->
