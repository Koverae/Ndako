<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Add') }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-100 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold">{{ __('Title') }}</span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="title" class="k-input" id="name-k" placeholder="e.g. Prepare Room #A03">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </h1>
                    </div>
                </div>
                <div class="mt-2 d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Description') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <textarea wire:model="description" id="description" class="p-0 m-0 textearea k-input"></textarea>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Type') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="type" id="" class="k-input">
                            <option value=""></option>
                            <option value="task">{{ __('Task') }}</option>
                            <option value="situation">{{ __('Situation') }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Room') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="room" id="" class="k-input">
                            <option value=""></option>
                            @foreach($rooms as $key => $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Priority') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="priority" id="" class="k-input">
                            <option value="low">{{ __('Low') }}</option>
                            <option value="medium">{{ __('Medium') }}</option>
                            <option value="high">{{ __('High') }}</option>
                            <option value="critical">{{ __('Critical') }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12 {{ $this->type === 'task' ? 'd-none' : '' }}" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Reported By') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model.live="reportedBy" id="" class="k-input">
                            <option value=""></option>
                            @foreach(current_company()->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        {{ __('Assigned To') }} :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="assignedTo" id="" class="k-input">
                            <option value=""></option>
                            @foreach(current_company()->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
        <button class="btn btn-primary" wire:click.prevent="addWorkItem">{{ __('Add') }}</button>
      </div>
    </div>
</div>
