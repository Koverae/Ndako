@section('page_title', "Choose a plan to continue managing your properties")


<section class="overflow-x-hidden page page-center" style="height: 100%;">

    <div class="row align-items-center g-4 started">
        <div class="col-lg d-none d-lg-block started-background">
        </div>
        <div class="col-lg">
            <div class="container py-4">
                <div class="mt-0 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800" wire:click="changeNew">Subscribe to Ndako</h1>
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

                    <div class="mb-3">
                      <label class="form-label">Choose a Plan</label>
                      <div class="form-selectgroup">
                        @foreach ($plans as $plan)
                        <label class="form-selectgroup-item">
                           <input type="radio" wire:model.live="selectedPlan" value="{{ $plan->tag }}" class="form-selectgroup-input">
                           <span class="form-selectgroup-label text-start">
                             <span class="text-black">{{ $plan->name }}</span> <br>
                             <span class="text-small">{{ format_currency(getFinalPrice($plan->price)) }} <s>{{ format_currency($plan->price) }}</s> 
                             {{-- <br>
                             @if($billingCycle == 'year')
                              ({{ format_currency($plan->price/12) }} / month)
                             @endif --}}
                             </span>
                           </span>
                        </label>
                        @endforeach
                      </div>
                    </div>

                    <div class="mb-2 form-footer">
                        <span wire:click="initiatePayment" class=" text-uppercase btn btn-primary w-100">
                            Subscribe Now
                        </span>
                    </div>

                    <span class="text-gray-600 text-muted">
                        Need help? <a href="https://ndako.koverae.com/contact-us" target="_blank" class="hover:underline">Contact us</a>.
                    </span>
                </form>


            </div>
        </div>
    </div>
</section>
