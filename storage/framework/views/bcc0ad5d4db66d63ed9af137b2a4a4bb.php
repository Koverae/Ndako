<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>">
    <title>Payment Successful 🎉</title>

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
      <div class="container py-4 container-tight">
        <div class="card card-md">
          <div class="card-body">
            <div class="mt-0 mb-2 text-center">
                <a href="#" class="navbar-brand navbar-brand-autodark">
                    <img src="<?php echo e(asset('assets/images/logo/logo-black.png')); ?>" width="130" height="52" alt="Tabler" class="navbar-brand-image">
                </a>
            </div>
            <h2 class="mb-3 text-center">Payment Successful 🎉</h2>
            <p class="mb-4 text-secondary fs-3">
                Thank you for your payment! Your subscription has been activated.
            </p>

            <div class="mt-4">
                <strong>Transaction ID:</strong> <?php echo e($data['reference']); ?> <br>
                <strong>Amount Paid:</strong> <?php echo e(format_currency($data['amount'] / 100, 2)); ?> <br>
                <strong>Status:</strong> <span class="badge bg-success"><?php echo e(ucfirst($data['status'])); ?></span>
            </div>

            <?php if(isset($subscription)): ?>
            <div class="mt-4 mb-2">
                <h4>📅 Subscription Details</h4>
                <strong>Plan Name:</strong> <?php echo e($subscription->plan_name); ?> <br>
                <strong>Next Payment Date:</strong> <?php echo e(\Carbon\Carbon::parse($subscription->next_billing_date)->format('F j, Y')); ?> <br>
                <strong>Billing Cycle:</strong> <?php echo e(ucfirst($subscription->billing_cycle)); ?>

            </div>
            <?php endif; ?>

            <div class="my-4">
              <a href="<?php echo e(route('dashboard')); ?>" class="text-white btn btn-primary text-uppercase w-100 fs-3">
                Go to Dashboard
              </a>
            </div>
            
          </div>
        </div>
      </div>
    </div>


    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" data-navigate-track></script>
</body>

</html>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/paystack/success.blade.php ENDPATH**/ ?>