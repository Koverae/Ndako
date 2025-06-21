<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>">
    <title><?php echo e(current_company()->name); ?> - <?php echo $__env->yieldContent('title'); ?></title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="<?php echo e(asset('assets/libs/list.js/dist/list.min.js')); ?>" data-navigate-track ></script>
    <script src="<?php echo e(asset('assets/libs/apexcharts/dist/apexcharts.min.js')); ?>" data-navigate-track ></script>
    <!-- Libs JS -->
    <?php echo $__env->yieldContent('styles'); ?>
    <!-- Scripts -->

    <script src="<?php echo e(asset('assets/js/koverae.js?'.time())); ?>" data-navigate-track></script>

    <!-- FullCalendar CSS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    

    <!-- Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <?php echo $__env->yieldContent('scripts'); ?>


</head>
<body>
    <script src="<?php echo e(asset('assets/js/demo-theme.min.js')); ?>" data-navigate-track></script>
    <main class="page">
        <!-- Navbar -->
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Navbar End -->

        <!-- Page Content -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- Page Content End -->

        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::components.notification-bell', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3223664511-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    </main>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('wire-elements-modal');

$__html = app('livewire')->mount($__name, $__params, 'lw-3223664511-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <!-- Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?> <!-- This is where scripts pushed with <?php $__env->startPush('scripts'); ?> will be loaded -->
    <!-- Custom JS -->
</body>

</html>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views\layouts\app.blade.php ENDPATH**/ ?>