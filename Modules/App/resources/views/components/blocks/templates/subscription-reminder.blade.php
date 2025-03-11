@props([
    'value',

])

@if(current_company()->team->subscription('main')->isOnTrial())
<div class="setting_block">
    <div class="mt-2 alert alert-warning">
        @php
            $subscription = current_company()->team->subscription('main');
            $daysLeft = $subscription->getTrialPeriodRemainingUsageIn('day');
            $hoursLeft = $subscription->getTrialPeriodRemainingUsageIn('hour');
        @endphp
    
        <p>⏳ Your trial will expire in 
            @if ($daysLeft >= 1)
                {{ $daysLeft }} days
            @else
                {{ $hoursLeft }} hours
            @endif
            ! <a href="#" target="__blank"><strong>Register your subscription</strong></a> or 
            <a href="#" target="__blank"><strong>buy a subscription</strong></a>
        </p>
    </div>
</div>
@endif

