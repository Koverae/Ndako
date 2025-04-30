<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Add Expense') }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold">{{ __('Name') }}</span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="e.g. Monthly internet subscription - Safaricom Fibre">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </h1>
                    </div>
                </div>


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Expense Category') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="category" class="k-input" id="model_0">
                        <option value="">{{ __("----- Select Category ------") }}</option>
                        @foreach ($categoryOptions as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Property') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="property" class="k-input" id="model_0">
                        <option value="">{{ __("----- Select Property ------") }}</option>
                        @foreach (current_company()->properties as $property)
                        <option value="{{ $property->id }}" {{ $property->id == current_property()->id ? 'selected' : '' }}>{{ $property->name }}</option>
                        @endforeach
                    </select>
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Room/Unit') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="propertyUnit" class="k-input" id="model_0">
                        <option value="">{{ __("----- Select Room/Unit ------") }}</option>
                        @foreach ($unitOptions as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} - {{ $unit->unitType->name }}</option>
                        @endforeach
                    </select>
                </div>
              </div>


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Amount') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="number" wire:model="amount" class="k-input" id="model_0" placeholder="e.g. 4500.00">
                </div>
              </div>


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Date') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="date" wire:model="date" class="k-input" id="model_0">
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Note') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="note" class="k-input" id="model_0" placeholder="e.g. 123 Riverside Drive, Westlands, Nairobi, Kenya">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
        <button class="btn btn-primary" wire:click.prevent="saveExpense">{{ __('Add') }}</button>
      </div>
    </div>
</div>
