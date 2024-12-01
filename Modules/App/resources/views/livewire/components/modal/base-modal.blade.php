<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ $title }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        @if(count($this->inputs()) >= 1)
        <div class="k_form_nosheet">
            <!-- Input -->
            @foreach($this->inputs() as $input)
            @if($input->position == 'top' && $input->tab == 'none')
            <x-dynamic-component
                :component="$input->component"
                :value="$input"
            >
            </x-dynamic-component>
            @endif
            @endforeach
            <!-- Top Form -->
            <div class="row">
                <!-- Left Side -->
                <div class="k_inner_group col-md-6 col-lg-6">
    
                    @foreach($this->inputs() as $input)
                        @if($input->position == 'left' && $input->tab == 'none')
                            <x-dynamic-component
                                :component="$input->component"
                                :value="$input"
                            >
                            </x-dynamic-component>
                        @endif
                    @endforeach
    
                </div>
                <!-- Right Side -->
                <div class="k_inner_group col-md-6 col-lg-6">
    
                    @foreach($this->inputs() as $input)
                        @if($input->position == 'right' && $input->tab == 'none')
                            <x-dynamic-component
                                :component="$input->component"
                                :value="$input"
                            >
                            </x-dynamic-component>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
      </div>
      <!-- Modal Buttons -->
      <div class="p-0 modal-footer">
            @foreach($this->buttons() as $button)
            <x-dynamic-component
                :component="$button->component"
                :value="$button"
            >
            </x-dynamic-component>
            @endforeach
        </div>
    </div>
</div>
