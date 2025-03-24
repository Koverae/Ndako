@extends('layouts.auth')

@section('page_title', 'Verify OTP')

@section('page_content')
<div class="page page-center">
  <div class="container py-4 container-tight">
    <div class="card card-md">
      <div class="card-body">
        <div class="mt-0 mb-2 text-center">
          <a href="#" class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('assets/images/logo/logo-black.png') }}" width="130" height="52" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>


        <form method="POST" action="{{ route('verify.store') }}">
            @csrf
            <p class="my-4 text-center">Please confirm your account by entering the authorization code sent to <strong>{{ auth()->user()->phone }}</strong>.</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-50" :status="session('status')" />

            <x-input-error :messages="$errors->get('two_factor_code')" class="mt-2" />
            <div class="my-5">
              <div class="row g-4">
                <div class="col">
                  <div class="row g-2">
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" autofocus maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="row g-2">
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="my-4">
              <label class="form-check">
                <input type="checkbox" name="dont_ask" class="form-check-input" />
                {{ __("Dont't ask for codes again on this device") }}
              </label>
            </div>
            <div class="form-footer">
              <div class="btn-list flex-nowrap">
                <button type="button" class="btn w-100">
                  {{ __('Cancel') }}
                </button>
                <button type="submit" class="capitalize btn btn-primary w-100 text-capitalize">
                  {{ __('Verify') }}
                </button>
              </div>
            </div>
            <div class="mt-3 text-center text-secondary">
                {{ new Illuminate\Support\HtmlString(__("It may take a minute to receive your code. Haven't received it? If not, click <a class=\"hover:underline\" href=\":url\">here</a>.", ['url' => route('verify.resend')])) }}
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection


{{-- @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input[type="number"]');
            const button = document.querySelector('button[type="submit"]');
            button.disabled = true;  // Initially disable the verify button

            inputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    input.value = input.value.slice(0, 1); // Ensure each input accepts only one character
                    const nextInput = inputs[index + 1];
                    const prevInput = inputs[index - 1];

                    // Move to next input if current has value
                    if (input.value && nextInput) {
                        nextInput.removeAttribute('disabled');
                        nextInput.focus();
                    }

                    // Check if all inputs are filled
                    button.disabled = !Array.from(inputs).every(input => input.value.length === 1);
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === "Backspace") {
                        if (input.value === '') {
                            // Focus and clear the previous input if the current is already empty
                            const prevInput = inputs[index - 1];
                            if (prevInput) {
                                e.preventDefault();  // Prevent the default backspace action
                                prevInput.focus();
                                prevInput.value = '';  // Clear the previous input
                                prevInput.removeAttribute('disabled');
                                button.disabled = !Array.from(inputs).every(input => input.value.length === 1); // Update button disabled state
                            }
                        } else {
                            input.value = ''; // Clear current input if it's not empty
                            button.disabled = !Array.from(inputs).every(input => input.value.length === 1); // Update button disabled state
                        }
                    }
                });
            });

            // Set focus on the first input on load
            inputs[0].focus();
        });
    </script>
@endsection  --}}

