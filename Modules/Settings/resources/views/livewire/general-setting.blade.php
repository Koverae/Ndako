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

            <!-- Paramètre Généraux -->
            <div class="cursor-pointer tab selected">
                <!-- App Icon -->
                <div class="icon d-none d-md-block">
                    <img src="{{ asset('assets/images/apps/settings.png')}}" alt="">
                </div>
                <!-- App Name -->
                <span class="app_name">
                    General Setting
                </span>
            </div>

        </div>

        <!-- Right Sidebar -->
        <div class="settings">
            <livewire:settings::settings.general :setting="settings()" />
        </div>
    </div>
</section>
<!-- Page Content End -->
