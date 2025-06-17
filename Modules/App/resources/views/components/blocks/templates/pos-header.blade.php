@props([
    'value',

])

<div class="setting_block">
    <div class="gap-5 p-3 app_settings_header d-md-flex d-block">
        <h3>{{ __('Restaurant') }}</h3>
        <!-- Box Input -->
        <div class="gap-4 d-flex">
            <select id="Fiscal Localization" class="k-input">
                <option value=""></option>
                @foreach($this->restaurants as $value => $text)
                <option value="{{ $value }}" {{ $this->pos->id == $value ? 'selected' : '' }}>{{ $text }}</option>
                @endforeach
            </select>
            <a href="{{ route('pos.create') }}" class="cursor-pointer text-primary font-weight-bold" style="font-weight: 600;"><i class="bi bi-plus-circle"></i> New Restaurant</a>
        </div>
        <!-- Box Input End -->
    </div>
    @if($this->pos->activeSession)
    <div class="mt-2 alert alert-warning">
        A session is currently opened for this Front Desk. Some settings can only be changed after the session is closed.
        <span class="cursor-pointer text-primary" style="font-weight: 600;" wire:click="closeSession">Click here to close session</span>
    </div>
    @endif
</div>

