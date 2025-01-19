<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Add Guest') }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold">{{ __('Name') }}</span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="e.g. Wanjiku Mwangi">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </h1>
                    </div>
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        @if($this->photo != null)
                        <img src="{{ $this->photo->temporaryUrl() }}" alt="image" class="img img-fluid">
                        @else
                        <img src="{{ $this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="image" class="img img-fluid">
                        @endif
                        <!-- Image selector -->
                        <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" onclick="document.getElementById('photo').click();">
                                <i class="bi bi-pencil"></i>
                                <input type="file" wire:model.blur="photo" id="photo" style="display: none;" />
                            </span>
                            @if($this->photo || $this->image_path)
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="$cancelUpload('photo')" wire:target="$cancelUpload('photo')">
                                <i class="bi bi-trash"></i>
                            </span>
                            @endif
                        </div>
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Email') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="email" wire:model="email" class="k-input" id="model_0" placeholder="e.g. wanjikumwangi@koverae.com">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Phone') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="tel" wire:model="phone" class="k-input" id="model_0" placeholder="e.g. +254745908026">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Date of Birth') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="date" wire:model="birthday" class="k-input" id="model_0">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Gender') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="gender" class="k-input" id="model_0">
                        <option value=""></option>
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                    </select>
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Job') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="job" class="k-input" id="model_0" placeholder="e.g. Software Engineer">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      {{ __('Address') }} :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="address" class="k-input" id="model_0" placeholder="e.g. 123 Riverside Drive, Westlands, Nairobi, Kenya">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
        <button class="btn btn-primary" wire:click.prevent="addGuest">{{ __('Add') }}</button>
      </div>
    </div>
</div>
