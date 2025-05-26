<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>

<style>
    #<?php echo e($mapId); ?> {
    <!--[if BLOCK]><![endif]--><?php if(! isset($attributes['style'])): ?>
        height: 100vh;
    <?php else: ?>
        <?php echo e($attributes['style']); ?>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    }
</style>

<div id="<?php echo e($mapId); ?>" <?php if(isset($attributes['class'])): ?>
 class='<?php echo e($attributes["class"]); ?>'
<?php endif; ?>
></div>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="<?php echo e('https://unpkg.com/leaflet@' . $leafletVersion . '/dist/leaflet.js'); ?>"
        crossorigin=""></script>
<script>

    var mymap = L.map('<?php echo e($mapId); ?>').setView([<?php echo e($centerPoint['lat'] ?? $centerPoint[0]); ?>, <?php echo e($centerPoint['long'] ?? $centerPoint[1]); ?>], <?php echo e($zoomLevel); ?>);
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $markers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <!--[if BLOCK]><![endif]--><?php if(isset($marker['icon'])): ?>
       var icon = L.icon({
        iconUrl: '<?php echo e($marker['icon']); ?>',
        iconSize: [<?php echo e($marker['iconSizeX'] ?? 32); ?> , <?php echo e($marker['iconSizeY'] ?? 32); ?>],
       });
     <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    var marker = L.marker([<?php echo e($marker['lat'] ?? $marker[0]); ?>, <?php echo e($marker['long'] ?? $marker[1]); ?>]
    <!--[if BLOCK]><![endif]--><?php if(isset($marker['icon'])): ?>
     , {icon: icon}
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    );
    marker.addTo(mymap);
    <!--[if BLOCK]><![endif]--><?php if(isset($marker['info'])): ?>
    marker.bindPopup(<?php echo json_encode($marker['info'], 15, 512) ?>);
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($tileHost === 'mapbox'): ?>
        let url<?php echo e($mapId); ?> = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=<?php echo e(config('maps.mapbox.access_token', null)); ?>';
    <?php elseif($tileHost === 'openstreetmap'): ?>
        let url<?php echo e($mapId); ?> = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    <?php else: ?>
        let url<?php echo e($mapId); ?> = '<?php echo e($tileHost); ?>';
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    L.tileLayer(url<?php echo e($mapId); ?>, {
        maxZoom: <?php echo e($maxZoomLevel); ?>,
        attribution: '<?php echo $attribution; ?>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(mymap);
</script>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/vendor/maps/components/leaflet.blade.php ENDPATH**/ ?>