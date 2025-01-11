<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Pay') }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">

              <div class="d-flex col-12 col-lg-6" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Journal') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="journal" class="k-input" id="model_0">
                        <option value=""></option>
                        @foreach($journals as $journal)
                        <option value="{{ $journal->id }}">{{ $journal->name }}</option>
                        @endforeach
                    </select>
                </div>
              </div>

              <div class="d-flex col-12 col-lg-6" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Amount') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="amount" class="k-input" id="model_0">
                </div>
              </div>

              <div class="d-flex col-12 col-lg-6" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Payment Method') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="paymentMethod" class="k-input" id="model_0">
                        <option value=""></option>
                        <option selected value="manual">{{ __('Manuel') }}</option>
                    </select>
                </div>
              </div>

              <div class="d-flex col-12 col-lg-6" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Payment Date') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="date" wire:model="date" class="k-input" id="model_0">
                </div>
              </div>


              <div class="d-flex col-12 col-lg-6" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Memo') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="memo" class="k-input" id="model_0" placeholder="">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
        <button class="btn btn-primary" wire:click.prevent="addPayment">{{ __('Pay') }}</button>
      </div>
    </div>
</div>
