@props([
    'value',

])

<div class="k_settings_box col-12 col-lg-12 k_searchable_setting" style="width: 100%;">

    <!-- Right pane -->
    <div class="k_setting_right_pane" style="width: 100%;">
        <div class="mt-1" style="width: 100%;">

            @if(current_company()->team->subscription('main'))
                <div class="mb-3 d-flex align-items-center">
                    <div class="me-3">
                        <img src="{{ asset('assets/images/logo/logo-circle-white.png') }}" style="height: 18px; width: 18px;" alt="">
                    </div>
                    <div>
                        <h1 class="mb-0 h2">{{ ucfirst(current_company()->team->subscription('main')->plan->name) }} Plan</h1>
                        <small class="text-muted">{{ ucfirst(current_company()->team->subscription('main')->status) }}</small>
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    @if (current_company()->team->subscription('main')->ends_at && current_company()->team->subscription('main')->starts_at && !current_company()->team->subscription('main')->cancels_at)
                        <span>Your team is subscribed since <b>{{ current_company()->team->subscription('main')->starts_at->diffForHumans() }}</b></span>
                        @if (now()->lessThan(current_company()->team->subscription('main')->ends_at))
                          <span>Next billing in <b>{{ getRemainingSubDays() }}</b>, on <b>{{ \Carbon\Carbon::parse(current_company()->team->subscription('main')->ends_at)->format('d M Y') }}</b></span>
                        @elseif(current_company()->team->subscription('main')->ends_at && now()->greaterThan(current_company()->team->subscription('main')->ends_at))
                        <span>
                            <strong>Your subscription has expired!</strong> Renew to continue using our Ndako.
                        </span>
                        @endif
                        <span>Your subscription code is <b>{{ current_company()->team->subscription('main')->paystack_subscription_code ?? 'N/A' }}</b></span>
                    @elseif(current_company()->team->subscription('main')->isOnTrial())

                    <span>
                        ⏳ Your trial will expire in {{ getRemainingTrialDays() }}!
                        <a href="{{ route('subscribe') }}"><strong>Upgrade now</strong></a> to continue managing your properties effortlessly with Ndako’s full suite of tools.
                    </span>
                    @elseif(current_company()->team->subscription('main')->cancels_at && !current_company()->team->subscription('main')->canceled_at)

                    <span>Your subscription was canceled <b>{{ current_company()->team->subscription('main')->cancels_at->diffForHumans() }}</b>.</span>
                    <span>Access remains until <b>{{ \Carbon\Carbon::parse(current_company()->team->subscription('main')->ends_at)->format('d M Y') }}</b>.</span>
                    @endif
                </ul>

                <div class="mt-2">
                    <a href="{{ route('subscribe') }}" class="gap-2 text-white btn btn-primary text-uppercase" >
                        <i class="bi bi-arrow-up-right-circle"></i> {{ current_company()->team->subscription('main')->isOnTrial() ? "Upgrade Now" : "Renew" }}
                    </a>
                    <span wire:click="cancelSubscription" wire:confirm='Do you really want to cancel your subscription?' class="btn btn-danger gap-2 text-uppercase {{ current_company()->team->subscription('main')->cancels_at ? 'd-none' : '' }}  {{ current_company()->team->subscription('main')->isActive() ? '' : 'd-none' }}">
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
