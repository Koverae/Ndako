<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset

    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
        <style>
            #modal-container { vertical-align: middle !important; }
            /* --- vertical scroll improvements (no visual changes) --- */
            .modal-scroll{
                max-height: calc(100dvh - 4rem);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch; /* momentum on iOS */
                overscroll-behavior: contain;      /* keep scroll inside modal */
            }
            @media (min-width: 640px){ /* sm and up */
                .modal-scroll{ max-height: 80vh; }
            }
        </style>
    @endisset

    <div
        x-data="LivewireUIModal()"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="closeModalOnEscape()"
        x-show="show"
        class="fixed inset-0 p-4"
        style="display:none; z-index: 9999999;"
    >
        <!-- Backdrop -->
        <div
            x-show="show"
            x-on:click="closeModalOnClickAway()"
            x-transition:enter="ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 will-change-[opacity]"
            aria-hidden="true"
        ></div>

        <!-- Wrapper: bottom sheet on mobile, centered on >= sm -->
        <div class="fixed inset-0 flex items-end sm:items-center sm:justify-center p-0 md:py-4 modal sm:p-4 overflow-y-auto">
            <div
                x-show="show && showActiveComponent"

                x-transition:enter="ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-10 sm:translate-y-6 sm:scale-[.98]"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-y-0 sm:scale-100"

                x-transition:leave="ease-in duration-600"
                x-transition:leave-start="opacity-100 translate-y-0 sm:translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-10 sm:translate-y-6 sm:scale-[.98]"

                x-trap.noscroll.inert="show && showActiveComponent"
                :class="[ modalWidth || '' ]"
                class="relative w-screen sm:w-full sm:max-w-lg md:max-w-2xl lg:max-w-3xl bg-white rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden mx-auto will-change-[transform,opacity]"
                id="modal-container"
                role="dialog"
                aria-modal="true"
            >
                <!-- Scrollable content area -->
                <div class="modal-scroll">
                    @forelse($components as $id => $component)
                        <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                            @livewire($component['name'], $component['arguments'], key($id))
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
