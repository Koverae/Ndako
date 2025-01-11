<div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
                <!-- Side Bar -->
              <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white d-none d-lg-block col-md-2 app-sidebar bg-view position-relative pe-1 ps-3" style="z-index: 500;">
                <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">

                  <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><i class="bi bi-list"></i> <?php echo e($this->headerText); ?></b></header>
                  <ul class="mb-4 ml-2">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->data(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-decoration-none kover-navlink panel-category selected' py-1 pe-0 ps-0 cursor-pointer">
                      <?php echo e($row->name); ?>

                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                  </ul>

                </form>
              </div>

            <!-- Map -->
            
            <iframe class="p-0 col-12 col-md-12 col-lg-10" height="700" src="https://www.openstreetmap.org/export/embed.html?bbox=30.673828125000004%2C-4.7078283752183046%2C42.93457031250001%2C2.141834969768584&amp;layer=mapnik&amp;marker=-1.2852925793638545%2C36.80419921875" style="border: 1px solid black"></iframe><br/>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/livewire/components/table/template/map.blade.php ENDPATH**/ ?>