@props([
    'value',

])

@if(current_company()->team->subscription('main')->isOnTrial())
<div class="setting_block">
    <div class="mt-2 alert alert-warning">
        <p>⏳ Your trial will expire in {{ current_company()->team->subscription('main')->getTrialPeriodRemainingUsageIn('day') }} days! <a href="#" target="__blank" class=""><strong>Register your subscription</strong></a> or <a href="#" target="__blank" class=""><strong>buy a subscription</strong></a></p>
    </div>
</div>
@endif

