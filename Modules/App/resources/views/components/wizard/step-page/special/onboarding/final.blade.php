@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Congratulations🎉! You've Made It! 🚀</h2>
            <p>Welcome aboard! Your journey starts here. Watch this quick tour to explore your new dashboard and make the most out of it.</p>
        </div>
        <div class="card-body">

            <!-- YouTube Video Embed -->
            <div class="aspect-w-16 aspect-h-100 rounded-lg shadow-lg overflow-hidden mb-6" style="height: 315px; ">
                <iframe class="w-100 h-100" src="{{ $this->videoUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                {{-- <iframe class="w-full h-full" src="{{ $this->videoUrl }}" frameborder="0" allowfullscreen></iframe> --}}
            </div>

            <div class="d-flex justify-content-between">
                <span>&nbsp;</span>
                <div class="mt-3 wizard-navigation text-end">
                    <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                    
                    <span wire:click="goToDashboard" type="submit" class="btn btn-primary go-next">
                        <span>🚀 Go to Dashboard</span>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
