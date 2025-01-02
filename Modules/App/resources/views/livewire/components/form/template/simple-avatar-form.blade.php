<div>
    <div class="k-form-sheet-bg">

        @if (session()->has('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span>{{ session('message') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="pb-2 mb-0 k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0">
            <!-- Action Bar -->
            @if($this->actionBarButtons())
                <div id="action-bar" class="flex-wrap gap-1 k-statusbar-buttons d-none d-lg-flex align-items-center align-content-around">

                    @foreach($this->actionBarButtons() as $action)
                    <x-dynamic-component
                        :component="$action->component"
                        :value="$action"
                        :status="$status"
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
                            :status="$status"
                        >
                        </x-dynamic-component>
                        @endforeach
                        <!--<li><hr class="dropdown-divider"></li>-->
                    </ul>
                </div>
            @endif

            <!-- Status Bar -->
            @if($this->statusBarButtons())
                <div id="status-bar" class="k-statusbar-buttons-arrow d-none d-md-flex align-items-center align-content-around ">

                    @foreach($this->statusBarButtons() as $status_button)
                    <x-dynamic-component
                        :component="$status_button->component"
                        :value="$status_button"
                        :status="$status"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
                <div id="status-bar" class="k-statusbar-buttons-arrow d-flex d-md-none align-items-center align-content-around ">

                    @foreach($this->statusBarButtons() as $status_button)
                        @if($this->status == $status_button->primary)
                        <x-dynamic-component
                            :component="$status_button->component"
                            :value="$status_button"
                            :status="$status"
                        >
                        </x-dynamic-component>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
        <form wire:submit.prepend="{{ $this->form() }}">
            @csrf
            <!-- Sheet Card -->
            <div class="k-form-sheet position-relative">
                {{-- <div class="box col-9">
                    <div class="k-folded-ribbon bg-danger">
                        <span class="word">
                            {{ __('Disconnected') }}
                        </span>
                    </div>
                </div> --}}

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
                    <!-- Employee Avatar -->
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

                </div>

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

                <!-- Tab Link -->
                @if($this->tabs())
                <div class="k_notebook_headers">
                    <!-- Tab Link -->
                    <ul class="flex-row overflow-x nav nav-tabs flex-nowrap border-bottom-0">
                        @foreach ($this->tabs() as $tab)
                        <li class="nav-item {{ $tab->condition == true ? 'd-none' : '' }}">
                            <a class="nav-link {{ $tab->key === 'general' ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $tab->key }}">
                                {{ $tab->label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <!-- Tab Link End -->
                </div>

                @endif

                <!-- Tabs content -->
                @if($this->tabs())
                    @foreach ($this->tabs() as $tab)
                    <x-dynamic-component
                        :component="$tab->component"
                        :value="$tab"
                    >
                    </x-dynamic-component>
                    @endforeach
                @endif


            </div>
        </form>
    </div>
    {{-- <div class="px-1 py-1 mt-2 k-chatter">
        <div class="top-0 gap-2 k-chatter-top position-sticky">
            <!-- Topbar -->
            <div class="flex-grow-0 flex-shrink-0 px-3 overflow-x-auto k-chatter-topbar d-flex">
                <button class="btn btn-primary">Envoyer un message</button>
                <span class="btn btn-secondary" style="margin-left: 5px;">
                    Prendre des notes
                </span>
                <div class="k-chatter-topbar-grow w-50 flex-grow-1 pe-2">

                </div>
                <div class="d-flex flex-grow-1 right">
                    <button class="btn btn-link"><i class="bi bi-paperclip" style="font-size: 18px;"></i></button>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Loading -->
    <div class="pb-1 cursor-pointer k-loading" wire:loading>
        <p>Loading ...</p>
    </div>
</div>
