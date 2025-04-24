@section('title', "Import File")

<!-- Control Panel -->
@section('control-panel')
<livewire:app::navbar.control-panel.import-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <div class="bg-white empty k_nocontent_help h-100">
        <img src="{{ asset('assets/images/illustrations/file.svg') }}"style="height: 350px" alt="">
        <p class="empty-title">{{__('Drop or upload a file to import')}}</p>
        <p class="empty-subtitle">{{ __('Excel files are recommended as formatting is automatic. But, you can also use .csv files') }}</p>
    </div>
</section>
<!-- Page Content -->