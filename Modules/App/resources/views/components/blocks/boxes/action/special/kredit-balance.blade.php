@props([
    'value'
])
<div class="p-3 ">
    <span>My balance:</span> <span class="text-muted fs-3"><b>{{ number_format(current_company()->team->wallet->balance) }}</b> ₭ (= {{ current_company()->team->wallet->balance }} SmS)</span>
</div>
