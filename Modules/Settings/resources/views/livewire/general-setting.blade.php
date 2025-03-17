@section('title', "Settings")

<!-- Control Panel -->
@section('control-panel')
<livewire:settings::navbar.setting-panel />
@endsection

<!-- Page Content -->
<section class="page-body">
    <!-- Settings -->
    <div class="k-row">
        <!-- Left Sidebar -->
        <div class="settings_tab border-end">

            <!-- General Settings -->
            <div class="cursor-pointer tab  {{ $this->view == 'general' ? 'selected' : '' }}" wire:click="changePanel('general')">
                <!-- App Icon -->
                <div class="icon d-none d-md-block">
                    <img src="{{ asset('assets/images/apps/settings.png')}}" alt="">
                </div>
                <!-- App Name -->
                <span class="app_name">
                    General Setting
                </span>
            </div>

            <!-- Properties -->
            <div class="tab cursor-pointer {{ $this->view == 'properties' ? 'selected' : '' }}" wire:click="changePanel('properties')">
                <!-- App Icon -->
                <div class="icon d-none d-md-block" >
                    <img src="{{ asset('assets/images/apps/reservation.png')}}" alt="">
                </div>
                <!-- App Name -->
                <span class="app_name">
                    Properties
                </span>
            </div>
            <!-- Properties End -->

            <!-- Channel Manager -->
            <div class="cursor-pointer tab {{ $this->view == 'channel-manager' ? 'selected' : '' }}" wire:click="changePanel('channel-manager')">
                <!-- App Icon -->
                <div class="icon d-none d-md-block">
                    <img src="{{ asset('assets/images/apps/channel-manager.png')}}" alt="">
                </div>
                <!-- App Name -->
                <span class="app_name">
                    Channel Manager
                </span>
            </div>
            <!-- Channel Manager End -->

        </div>

        <!-- Right Sidebar -->
        <div class="settings">
            @if($view == 'general')
            <livewire:settings::settings.general :setting="settings()" />
            @elseif($view == 'properties')
            <livewire:properties::settings.property-setting :setting="settings()" />
            @elseif($view == 'channel-manager')
            <livewire:channelmanager::settings.channel-manager-setting :setting="settings()" />
            @endif
        </div>
    </div>
</section>
<!-- Page Content End -->
