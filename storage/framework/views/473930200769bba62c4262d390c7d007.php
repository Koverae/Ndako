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
    <link href="<?php echo e(asset('assets/css/koverae-flags.min.css?'.time())); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets/css/demo.min.css?'.time())); ?>" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body  class="d-flex flex-column">
    <script src="<?php echo e(asset('assets/js/demo-theme.min.js?'.time())); ?>"></script>
    
    <?php echo $__env->yieldContent('page_content'); ?>
    
    <!-- Libs JS -->
    <!-- Koverae Core -->
    <script src="<?php echo e(asset('assets/js/koverae.min.js?'.time())); ?>" defer></script>
    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" defer></script>
    <script src="<?php echo e(asset('assets/js/demo.min.js?'.time())); ?>" defer></script>
  </body>
</html><?php /**PATH D:\My Laravel Startup\koverae-saas\resources\views/layouts/auth.blade.php ENDPATH**/ ?>