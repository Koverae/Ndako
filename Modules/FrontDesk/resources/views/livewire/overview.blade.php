@section('title', "Point Of Sales")

<!-- Control Panel -->
@section('control-panel')
<livewire:frontdesk::navbar.control-panel.overview-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <livewire:frontdesk::table.front-desk-table />
</section>
<!-- Page Content -->
