<div>
    <div class="k-form-sheet-bg">
        <!-- Notify -->
        {{-- <x-notify::notify /> --}}
        <form wire:submit.prevent="{{ $this->form() }}">
            @csrf
            <div class="pb-2 mb-0 k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0">
                <!-- Action Bar -->
                @if($this->actionBarButtons())
                    <div id="action-bar" class="flex-wrap gap-1 k-statusbar-buttons d-none d-lg-flex align-items-center align-content-around">

                        @foreach($this->actionBarButtons() as $action)
                        <x-dynamic-component
                            :component="$action->component"
                            :value="$action"
                            :status="'none'"
                        >
                        </x-dynamic-component>
                        @endforeach

                    </div>
                    <!-- Dropdown button -->
                    <div class="btn-group d-lg-none">
                        <span class="btn btn-dark buttons dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </span>
                        <ul class="dropdown-menu">
                            @foreach($this->actionBarButtons() as $action)
                            <x-dynamic-component
                                :component="$action->component"
                                :value="$action"
                                :status="'none'"
                            >
                            </x-dynamic-component>
                            @endforeach
                            <!--<li><hr class="dropdown-divider"></li>-->
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Sheet Card -->
            <div class="k-form-sheet position-relative">
                <!-- Capsule -->
                @if(count($this->capsules()) >= 1)
                <div class="gap-1 overflow-x-auto overflow-y-hidden k-horizontal-asset mb-md-3" id="k-horizontal-capsule">
                    @foreach($this->capsules() as $capsule)
                    <x-dynamic-component
                        :component="$capsule->component"
                        :value="$capsule"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
                @endif
                <!-- Capsule -->

                <!-- title-->
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'top-title' && $input->tab == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                    <!-- Avatar -->
                    @if($this->photo)
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        @if($this->photo != null)
                        <img src="{{ $this->photo->temporaryUrl() }}" alt="image" class="img img-fluid">
                        @else
                        <img src="{{ $this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/'.$default_img.'.png') }}" alt="image" class="img img-fluid">
                        @endif
                        <!-- <small class="k_button_icon">
                            <i class="align-middle bi bi-circle text-success"></i>
                        </small>-->
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
                    @endif
                    <!-- Avatar -->
                </div>

                <div class="row align-items-start">

                    <!-- Left Side -->
                    <div class="k_inner_group col-lg-6">
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'left' && $input->tab == 'none' && $input->group == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>

                    <!-- Right Side -->
                    <div class="k_inner_group col-lg-6">
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'right' && $input->tab == 'none' && $input->group == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row align-items-start">
                    @foreach($this->groups() as $group)
                    <x-dynamic-component
                        :component="$group->component"
                        :value="$group"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</div>
