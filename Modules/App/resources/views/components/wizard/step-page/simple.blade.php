@props([
    'value',
])
<div class="container {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
    {{ $value->label }}
</div>