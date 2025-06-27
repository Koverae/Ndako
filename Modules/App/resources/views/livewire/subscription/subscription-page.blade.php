
@section('page_title', "Choose a plan to continue managing your properties")


<section class="overflow-x-hidden page page-center" style="height: 100%;">

    <div class="row align-items-center g-4 started">
        <div class="col-lg d-none d-lg-block started-background">
        </div>
        <div class="col-lg">
            <div class="container py-4">
                <div class="mt-0 mb-2">
                    @if($renew)
                    <h1 class="text-3xl font-bold text-gray-800">Renew your Ndako Subscription</h1>
                    @else
                    <h1 class="text-3xl font-bold text-gray-800">Subscribe to Ndako</h1>
                    @endif

                    <p class="mt-2 text-lg text-gray-600">
                        Keep your property management running smoothly! Choose a plan now to continue accessing all the tools you need, seamlessly and without interruption
                    </p>
                </div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <!-- Session Status -->

                <form class="row" id="getStarted">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Number of Units/Rooms</label>
                        <input type="number" wire:model.live="roomCount" min="1" class="form-control" placeholder="Enter number of rooms">
                        <small class="text-muted">Enter the total number of rooms you manage or plan to manage.</small>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Billing Cycle</label>
                      <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                          <input type="radio" wire:model.live="billingCycle" value="month" class="form-selectgroup-input">
                          <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/circle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                            Monthly</span>
                        </label>
                        <label class="form-selectgroup-item">
                          <input type="radio" wire:model.live="billingCycle" value="year" class="form-selectgroup-input">
                          <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/square -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>
                            Yearly</span>
                        </label>
                      </div>

                    </div>

                    <div class="mb-2">
                      <label class="form-label">Choose a Plan</label>
                      <div class="form-selectgroup">
                        @foreach ($plans as $plan)
                        <label class="form-selectgroup-item">
                           <input type="radio" wire:model.live="selectedPlan" value="{{ $plan->tag }}" class="form-selectgroup-input">
                           <span class="form-selectgroup-label text-start">
                             <span class="text-black">{{ $plan->name }}</span> <br>
                             <span class="text-small">
                                {{ format_currency($plan->discounted_price * max(1, $roomCount)) }} <s>{{ format_currency($plan->price * max(1, $roomCount)) }}</s>
                             </span>
                           </span>
                        </label>
                        @endforeach
                      </div>
                    </div>

                    <div class="mb-2 col-md-12 col-lg-12">
                        <label class="form-label">Invoice Period</label>
                        <div class="number-input-wrapper @error('invoicePeriod') is-invalid @enderror">
                            <span class="btn btn-link minus" wire:click="decreaseInvoicePeriod">−</span>
                            <input type="number" id="number-input" min="1" wire:model="invoicePeriod" class="number-input" />
                            <span class="btn btn-link plus" wire:click="increaseInvoicePeriod">+</span>
                        </div>
                        <span>{{ ucfirst($billingCycle) }}(s)</span>
                        @error('invoicePeriod')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if($selectedPlan)
                    <span>You are about to {{ $renew ? "renew" : "subscribe" }} to <strong>{{ getPlan($selectedPlan) }}</strong> for <b>{{ format_currency($amount) }}</b> to manage your <b>{{ $roomCount }} rooms/units</b> for {{ $invoicePeriod }} {{ $billingCycle.'(s)' }}.</span>
                    @endif

                    <div class="mb-2 form-footer">
                        <span wire:click="initiatePayment" class="{{ $selectedPlan && $invoicePeriod >= 1 ? '' : 'disabled'}} text-uppercase btn btn-primary w-100">
                            Subscribe Now
                        </span>
                    </div>

                    <span class="text-sm text-gray-600 text-muted">
                        Not sure which plan is best for your needs? Want more details about what each plan includes? <a href="https://ndako.koverae.com#pricing" target="__blank">See our pricing</a> to learn more or reach out to us for help!
                    </span>

                </form>


            </div>
        </div>
    </div>
</section>
