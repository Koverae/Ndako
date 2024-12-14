@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp
<div>
    @if ($paginator->hasPages())
        <nav class="gap-2 k_pager align-items-center d-flex">
            <span class="k_page_counter">
                <!-- Page value -->
                <span class="border-transparent k_page_value d-inline-block border-bottom mb-n1">
                    {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }}
                </span>
                <span>/</span>
                <span class="k_page_limit">
                    {{ $paginator->total() }}
                </span>
            </span>
            <span class="k_cp_switch_buttons btn-group d-print-none">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button class="btn btn-secondary k-paginate-arrow" disabled>
                        <i class="bi bi-arrow-left"></i>
                    </button>
                @else
                    <button class="btn btn-secondary k-paginate-arrow" wire:click="previousPage" wire:loading.attr="disabled">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button class="btn btn-secondary k-paginate-arrow" wire:click="nextPage" wire:loading.attr="disabled">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                @else
                    <button class="btn btn-secondary k-paginate-arrow" disabled>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                @endif
            </span>
        </nav>
    @endif
</div>
