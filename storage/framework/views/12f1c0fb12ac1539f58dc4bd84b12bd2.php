<section class="container-fluid <?php echo e($tab == 'cart' ? 'd-none d-lg-block' : ''); ?> col-lg-7 col-md-12 h-screen-d" id="product-box"
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
        placeholder="<?php echo e(__('Search products, e.g. “Latte”, “Burger”...')); ?>"
        aria-label="<?php echo e(__('Search products')); ?>"
        wire:model.live="searchQuery"
        id="prod-search-input">
      <!--[if BLOCK]><![endif]--><?php if(!empty($searchQuery)): ?>
        <button class="btn-clear" type="button" aria-label="<?php echo e(__('Clear search')); ?>"
                wire:click="$set('searchQuery','')">
          <i class="bi bi-x-circle"></i>
        </button>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Categories (scrollable pills) -->
    <div class="cat-scroll" role="tablist" aria-label="<?php echo e(__('Product categories')); ?>">
      <button
        type="button"
        class="cat-pill <?php echo e($selectedCategoryId == null ? 'active' : ''); ?>"
        wire:click="selectCategory('<?php echo e(0); ?>')"
        role="tab" aria-selected="<?php echo e($selectedCategoryId == null ? 'true' : 'false'); ?>">
        <i class="bi bi-house-fill me-1" aria-hidden="true"></i><?php echo e(__('All')); ?>

      </button>

      <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productCategoryOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button
          type="button"
          class="cat-pill <?php echo e($selectedCategoryId == $category->id ? 'active' : ''); ?>"
          wire:click="selectCategory('<?php echo e($category->id); ?>')"
          role="tab" aria-selected="<?php echo e($selectedCategoryId == $category->id ? 'true' : 'false'); ?>">
          <?php echo e($category->name); ?>

        </button>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
  </div>

  <!-- Product Grid -->
  <div class="product-grid">
    
    

    
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4">
      <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $productOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col">
          <article class="p-card"
                   role="button"
                   tabindex="0"
                   wire:click="addToCart('<?php echo e($product->id); ?>')"
                   wire:key="prod-<?php echo e($product->id); ?>">
            <div class="p-media">
              
              <span class="p-badge d-none d-xl-inline-flex"><i class="bi bi-star-fill me-1" aria-hidden="true"></i><?php echo e(__('Popular')); ?></span>

              
              <img
                src="<?php echo e($product->image_path ? Storage::url('avatars/' . $product->image_path) . '?v=' . time() : asset('assets/images/default/product.png')); ?>"
                alt="<?php echo e($product->product_name); ?>"
                loading="lazy" decoding="async">
            </div>

            <div class="p-info">
              <div class="text-truncate">
                <div class="p-name text-truncate"><?php echo e($product->product_name); ?></div>
                <div class="p-meta text-truncate"><?php echo e($product->category->name ?? ''); ?></div>
              </div>
              <div class="p-price"><?php echo e(format_currency($product->product_price)); ?></div>
            </div>

            <div class="p-cta pt-0 pb-2 px-2">
              <button type="button" class="btn btn-light btn-sm fw-semibold w-100" aria-label="<?php echo e(__('Add to cart')); ?>">
                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> <?php echo e(__('Add')); ?>

              </button>
            </div>
          </article>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
          <div class="prod-empty">
            <div class="icon mb-2"><i class="bi bi-emoji-neutral" aria-hidden="true"></i></div>
            <div class="fw-bold"><?php echo e(__('No products match your filters')); ?></div>
            <div class="small"><?php echo e(__('Try adjusting the search or category.')); ?></div>
          </div>
        </div>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    
  </div>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/partials/pos/products.blade.php ENDPATH**/ ?>