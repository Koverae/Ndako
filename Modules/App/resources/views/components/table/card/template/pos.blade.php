@props([
    'value',
    'model',
    'id'
])

<div class="mb-1 col-md-6" style="border-left: 4px solid #0E6163">
    <div class="card">
        <div class="p-2 card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a class="text-decoration-none flex-grow-1" wire:navigate href="{{ $this->showRoute($id) }}">
                    <h5 class="m-0 mb-2 card-title"> {{ $model[$value->title] }} Y</h5>
                </a>
                <div class="dropdown ms-2">
                    <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear fs-3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a wire:navigate href="#" class="dropdown-item">
                            {{ __('Orders') }}
                        </a>
                        <a wire:navigate href="#" class="dropdown-item">
                            {{ __('Sessions') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column text-truncate">
                @foreach($this->data as $data)
                    <span class="mb-1 cursor-pointer text-truncate w-100">{{ $model[$data] }}</span>
                @endforeach
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('pos.ui', $id) }}" target="_blank" class="mt-2 btn btn-primary">{{ __('Open Session') }}</a>
            </div>
        </div>
    </div>
</div>
