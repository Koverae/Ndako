<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo $__env->yieldContent('page_title'); ?> | <?php echo e(config('app.name')); ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>" />
    <!-- CSS files -->
    <link href="<?php echo e(asset('assets/css/koverae.css?'.time())); ?>" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(env('GOOGLE_RECAPTCHA_KEY')); ?>"></script>

    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" data-navigate-track></script>

    <!-- Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

  </head>
  <body  class="d-flex flex-column">
    <script src="<?php echo e(asset('assets/js/demo-theme.min.js?'.time())); ?>"></script>

    <?php echo $__env->yieldContent('page_content'); ?>

    <!-- Libs JS -->
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <!-- Koverae Core -->
  </body>
</html>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/layouts/auth.blade.php ENDPATH**/ ?>