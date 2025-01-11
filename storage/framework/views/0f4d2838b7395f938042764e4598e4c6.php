<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>">
    <title><?php echo e(current_company()->name); ?> - <?php echo $__env->yieldContent('title'); ?></title>

    <!-- CSS -->
    <link href="<?php echo e(asset('assets/css/koverae.css?'.time())); ?>" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets/css/demo.min.css?'.time())); ?>" rel="stylesheet"/>
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha384-kr7knlC+7+03I2GzYDBHmxOStG8VIEyq6whWqn2oBoo1ddubZe6UjI+P5bn/f8O5" data-navigate-track/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha384-kgpA7T5GkjxAeLPMFlGLlQQGqMAwq8ciFnjsbPvZaFUHZvbRYPftvBcRea/Gozbo" data-navigate-track></script>
    <!-- Leaflet.js CSS -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="<?php echo e(asset('assets/libs/list.js/dist/list.min.js')); ?>" data-navigate-track ></script>
    <script src="<?php echo e(asset('assets/libs/apexcharts/dist/apexcharts.min.js')); ?>" data-navigate-track ></script>
    <!-- Libs JS -->

    <!-- Scripts -->
    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" data-navigate-track></script>
    
    <!-- Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</head>
<body>
    <script src="<?php echo e(asset('assets/js/demo-theme.min.js')); ?>" data-navigate-track></script>
    <main class="page">
        <!-- Navbar -->
        <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Navbar End -->

        <!-- Page Content -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- Page Content End -->

    </main>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('wire-elements-modal');

$__html = app('livewire')->mount($__name, $__params, 'lw-262326350-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <!-- Custom JS -->
    <!-- Custom JS -->

</body>
</html>
<?php /**PATH D:\My Laravel Startup\koverae-saas\resources\views/layouts/app.blade.php ENDPATH**/ ?>