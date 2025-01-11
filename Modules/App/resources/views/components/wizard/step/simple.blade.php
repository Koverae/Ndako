@props([
    'value',
])

<div class="step cursor-pointer {{ $this->currentStep == $value->key ? 'active' : '' }}" data-bs-placement="bottom" title="{{ $value->label }}">&nbsp;</div>
