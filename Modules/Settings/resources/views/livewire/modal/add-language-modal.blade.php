<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Add Language') }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group">
              <div class="d-flex" style="margin-bottom: 8px;">
                  <!-- Input Label -->
                  <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                      <label class="k_form_label">
                        {{ __('Language') }} :
                      </label>
                  </div>
                  <!-- Input Form -->
                  <div class="k_cell k_wrap_input flex-grow-1">
                      <select wire:model.blur="language" class="k-input" style="padding: 1px 10px 1px 0; width: 372px;" id="model_0">
                          <option value=""></option>
                          @foreach($languages as $language)
                              <option value="{{ $language->id }}"> <img src="{{ asset("assets/images/flags/". $language->icon .".svg") }}" alt=""> {{ $language->name }}</option>
                          @endforeach
                      </select>@error('language') <span class="error">{{ $message }}</span> @enderror
                  </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
        <button class="btn btn-primary" wire:click.prevent="addLanguage('{{ $language->id }}')" onclick="Livewire.dispatch('openModal', {component: 'settings::modal.switch-language-modal', arguments: { language: {{ $this->language }} }})">{{ __('Add') }}</button>
      </div>
    </div>
</div>
