<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>">
    <title>Payment Failed ❌</title>

    <!-- CSS -->
    <link href="<?php echo e(asset('assets/css/koverae.css?'.time())); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets/css/koverae-flags.min.css?'.time())); ?>" rel="stylesheet"/>
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="<?php echo e(asset('assets/libs/list.js/dist/list.min.js')); ?>" data-navigate-track ></script>
    <!-- Libs JS -->
    <?php echo $__env->yieldContent('styles'); ?>

</head>
<body>
    <script src="<?php echo e(asset('assets/js/demo-theme.min.js')); ?>" data-navigate-track></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="card card-md">
          <div class="card-body">
            <div class="mt-0 mb-2 text-center">
                <a href="#" class="navbar-brand navbar-brand-autodark">
                    <img src="<?php echo e(asset('assets/images/logo/logo-black.png')); ?>" width="130" height="52" alt="Tabler" class="navbar-brand-image">
                </a>
            </div>
            <h2 class="mb-3 text-center">Payment Failed ❌</h2>
            <p class="text-secondary mb-4 fs-3">
                Oops! Something went wrong with your payment.
            </p>

            <div class="my-4">
              <a href="" class="btn btn-primary text-uppercase w-100 fs-3">
                Try Again
              </a>
              <a href="https://ndako.koverae.com/contact-us?utm=app" class="btn btn-secondary">Contact Support</a>
            </div>
          </div>
        </div>
      </div>
    </div>


    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" data-navigate-track></script>
</body>

</html>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\paystack\error.blade.php ENDPATH**/ ?>