@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
    <div class="m-3 text-center position-relative">
        <img src="{{ asset('assets/images/illustrations/kwame-bot/kwame-1.svg') }}" alt="" class="img-fluid">
        <h1 class="top-0 m-3 h1 position-absolute end-0 w-50" style="font-size: 30px;">
            {{ __('Welcome! Let’s add your property in a few easy steps.') }}
        </h1>
    </div>
</div>
