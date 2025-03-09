@props([
    'value',

])

<div class="k_settings_box col-12 col-lg-12 k_searchable_setting" style="width: 100%;">

    <!-- Right pane -->
    <div class="k_setting_right_pane" style="width: 100%;">
        <div class="mt-1" style="width: 100%;">

            @if(current_company()->team->subscription('main'))
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: #007bff;"></i>
                    </div>
                    <div>
                        <h1 class="mb-0 h2">{{ ucfirst(current_company()->team->subscription('main')->plan->name) }} Plan</h1>
                        <small class="text-muted">{{ ucfirst(current_company()->team->subscription('main')->status) }}</small>
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    @if (current_company()->team->subscription('main')->ends_at && current_company()->team->subscription('main')->starts_at)
                        <span>Your team is subscribed since <b>{{ current_company()->team->subscription('main')->starts_at->diffForHumans() }}</b></span>
                        @if (now()->lessThan(current_company()->team->subscription('main')->ends_at))
                          <span>Next billing in <b>{{ (int) now()->diffInDays(current_company()->team->subscription('main')->ends_at) }} days</b></span>
                        @elseif(current_company()->team->subscription('main')->ends_at && now()->greaterThan(current_company()->team->subscription('main')->ends_at))
                        <span>
                            <strong>Your subscription has expired!</strong> Renew to continue using our Ndako.
                        </span>
                        @endif
                        <span>Your subscription code is <b>{{ current_company()->team->subscription('main')->paystack_subscription_code ?? 'N/A' }}</b></span>
                    @elseif(current_company()->team->subscription('main')->isOnTrial())
                    <span>⏳ Your trial will expire in {{ current_company()->team->subscription('main')->getTrialPeriodRemainingUsageIn('day') }} days! <a href="#" target="__blank" class=""><strong>Register your subscription</strong></a> or <a href="#" target="__blank" class=""><strong>buy a subscription</strong></a></span>
                    @endif
                </ul>

                <div class="mt-2">
                    <a href="#" class="btn btn-primary text-white disabled" >
                        <i class="bi bi-arrow-up-right-circle"></i> Upgrade Plan
                    </a>
                    <span wire:confirm='Do you really want to cancel your subscription?' class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Cancel Subscription
                    </span>
                </div>

            @else
                <p>No active subscription found.</p>
                <a href="{{ route('subscription.plans') }}" class="btn btn-primary">
                    <i class="bi bi-box-seam"></i> Choose a Plan
                </a>
            @endif

        </div>
    </div>

</div>