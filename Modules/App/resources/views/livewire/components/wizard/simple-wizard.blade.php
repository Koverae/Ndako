<div>
    <div class="k-wizard">
        <!-- Steps -->
        @if(count($this->steps()) >= 1)
        <!-- Steps Navigation -->
        <div class="wizard-steps">
            @foreach($this->steps() as $step)
            <x-dynamic-component
                :component="$step->component"
                :value="$step"
            >
            </x-dynamic-component>
            @endforeach
        </div>
        <!-- Steps Navigation End -->
        @endif
        <!-- Steps End -->

        <!-- Step Content -->
        <div class="wizard-content position-relative" style="height: auto;">
            @foreach($this->stepPages() as $page)
            <x-dynamic-component
                :component="$page->component"
                :value="$page"
            >
            </x-dynamic-component>
            @endforeach
        </div>
        <div class="mt-3 wizard-navigation position-absolute">
            <button class="btn cancel" wire:click="goToPreviousStep" {{ $currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <button class="btn btn-primary go-next" wire:click="goToNextStep" {{ $currentStep == count($this->steps()) - 1 ? 'disabled' : '' }}>Continue</button>
        </div>
        <!-- Step Content End -->
    </div>
</div>
