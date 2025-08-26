<section class="container-fluid {{ $tab == 'cart' ? 'd-none d-lg-block' : '' }} col-lg-7 col-md-12 h-screen-d" id="product-box"
         x-data="{ stuck:false }"
         x-init="
           const tb = $el.querySelector('.prod-toolbar');
           const io = new IntersectionObserver(([e]) => (stuck = !e.isIntersecting), { threshold: [1] });
           const sentinel = document.createElement('div'); sentinel.style.position='absolute'; sentinel.style.top='-1px'; sentinel.style.height='1px'; sentinel.style.width='1px';
           tb.prepend(sentinel); io.observe(sentinel);
         ">
  <!-- Sticky Toolbar -->
  <div class="prod-toolbar" :class="stuck && 'stuck'">
    <!-- Search -->
    <div class="prod-search">
      <i class="bi bi-search" aria-hidden="true"></i>
      <input
        type="text"
        class="form-control"
        placeholder="{{ __('Search products, e.g. “Latte”, “Burger”...') }}"
        aria-label="{{ __('Search products') }}"
        wire:model.live="searchQuery"
        id="prod-search-input"
        :disabled="!online" :title="!online ? '{{ __('Unavailable offline') }}' : ''">
      @if(!empty($searchQuery))
        <button class="btn-clear" type="button" aria-label="{{ __('Clear search') }}"
                wire:click="$set('searchQuery','')">
          <i class="bi bi-x-circle"></i>
        </button>
      @endif
    </div>

    <!-- Categories (scrollable pills) -->
    <div class="cat-scroll" role="tablist" aria-label="{{ __('Product categories') }}">
      <button
        type="button"
        class="cat-pill {{ $selectedCategoryId == null ? 'active' : '' }}"
        wire:click="selectCategory('{{ 0 }}')"
        role="tab" aria-selected="{{ $selectedCategoryId == null ? 'true' : 'false' }}"
        :disabled="!online" :title="!online ? '{{ __('Unavailable offline') }}' : ''">
        <i class="bi bi-house-fill me-1" aria-hidden="true"></i>{{ __('All') }}
      </button>

      @foreach ($productCategoryOptions as $category)
        <button
          type="button"
          class="cat-pill {{ $selectedCategoryId == $category->id ? 'active' : '' }}"
          wire:click="selectCategory('{{ $category->id }}')"
          role="tab" aria-selected="{{ $selectedCategoryId == $category->id ? 'true' : 'false' }}"
          :disabled="!online" :title="!online ? '{{ __('Unavailable offline') }}' : ''">
          {{ $category->name }}
        </button>
      @endforeach
    </div>
  </div>

  <!-- Product Grid -->
  <div class="product-grid">
    {{-- Loading skeletons (while searching / filtering) --}}
    {{-- <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4"
         wire:loading.delay
         wire:target="searchQuery,selectedCategoryId,selectCategory"
         role="status" aria-live="polite" aria-busy="true">
      <span class="visually-hidden">{{ __('Loading products…') }}</span>

      @for ($i = 0; $i < 8; $i++)
        @php
          $wTitle = ['72%','84%','66%'][$i % 3];
          $wMeta  = ['50%','58%','42%'][$i % 3];
          $wPrice = ['64px','56px','72px'][$i % 3];
        @endphp
        <div class="col">
          <article class="p-card s-card" aria-hidden="true">
            <!-- image placeholder keeps exact card ratio -->
            <div class="p-media skeleton">
              <img
                src="{{ asset('assets/images/default/product.png') }}">
            </div>

            <!-- text placeholders aligned like real card -->
            <div class="p-info">
              <div class="flex-grow-1" style="min-width:0">
                <div class="skeleton s-line mb-2" style="width: {{ $wTitle }}"></div>
                <div class="skeleton s-line" style="width: {{ $wMeta }}"></div>
              </div>
              <div class="skeleton s-line" style="width: {{ $wPrice }}; height:18px; border-radius:8px"></div>
            </div>

            <!-- button placeholder -->
            <div class="p-cta pt-0 pb-2 px-2">
              <div class="skeleton s-line" style="height:34px; border-radius:12px; width:100%"></div>
            </div>
          </article>
        </div>
      @endfor
    </div> --}}

    {{-- Products --}}
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4">
      @forelse ($productOptions as $product)
        <div class="col">
          <article class="p-card"
                   role="button"
                   tabindex="0"
                   wire:key="prod-{{ $product->id }}">
            <div class="p-media">
              {{-- badge (example: popular/infinite stock flag) --}}
              <span class="p-badge d-none d-xl-inline-flex"><i class="bi bi-star-fill me-1" aria-hidden="true"></i>{{ __('Popular') }}</span>

              {{-- image --}}
              <img
                src="{{ $product->image_path ? Storage::url('avatars/' . $product->image_path) . '?v=' . time() : asset('assets/images/default/product.png') }}"
                alt="{{ $product->product_name }}"
                loading="lazy" decoding="async">
            </div>

            <div class="p-info">
              <div class="text-truncate">
                <div class="p-name text-truncate">{{ $product->product_name }}</div>
                <div class="p-meta text-truncate">{{ $product->category->name ?? '' }}</div>
              </div>
              <div class="p-price">{{ format_currency($product->product_price) }}</div>
            </div>

            <div class="p-cta pt-0 pb-2 px-2">
                <button 
                    type="button" 
                        class="btn btn-light btn-sm fw-semibold w-100"
                            wire:click="addToCart('{{ $product->id }}')" 
                                aria-label="{{ __('Add to cart') }}">
                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> {{ __('Add') }}
                </button>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="prod-empty">
            <div class="icon mb-2"><i class="bi bi-emoji-neutral" aria-hidden="true"></i></div>
            <div class="fw-bold">{{ __('No products match your filters') }}</div>
            <div class="small">{{ __('Try adjusting the search or category.') }}</div>
          </div>
        </div>
      @endforelse
    </div>

    {{-- (Optional) pagination placeholder if you enable it later --}}
    {{-- <div class="mt-2">{{ $productOptions->links() }}</div> --}}
  </div>
</section>
