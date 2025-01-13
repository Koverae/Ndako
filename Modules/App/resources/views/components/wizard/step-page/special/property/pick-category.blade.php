@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="m-3 text-center">
        <h1 class="h1">{{ __('From the list below, which property category is similar to your place?') }}</h1>
    </div>
    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">
            <div class="row">
                <!-- Category -->
                @foreach($this->propertyType['hotel'] as $category)
                <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" wire:click="pickCategory('{{ $category['slug'] }}')" style="min-height: 122px;">
                    <div class="p-2 border rounded" style="min-height: 122px;">
                        <h3 class="h3">{{ $category['name'] }}</h3>
                        <div class="mt-3">
                            <p>
                                {{ $category['description'] }}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>&nbsp;</span>
                            <span class="{{ $this->category == $category['slug'] ? '' : 'd-none' }} selected-card text-end"><i class="fas fa-check-circle"></i></span>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Category End -->
            </div>
        </div>
    </div>
</div>
