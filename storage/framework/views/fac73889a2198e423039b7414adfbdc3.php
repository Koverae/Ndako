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
            <div id="map" style="height: 600px;"></div>
            
            
        </div>
    </div>
    <!--[if BLOCK]><![endif]--><?php if($this->data()->count() == 0): ?>
    <div class="bg-white empty k_nocontent_help h-100">
        <img src="<?php echo e(asset('assets/images/illustrations/errors/419.svg')); ?>"style="height: 350px" alt="">
        <p class="empty-title"><?php echo e($this->emptyTitle()); ?></p>
        <p class="empty-subtitle"><?php echo e($this->emptyText()); ?></p>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<script>
document.addEventListener('livewire:load', function () {
    // Initialize Leaflet map
    var map = L.map('map').setView([0, 0], 2); // Default to world view

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);

    // Properties from Livewire
    var properties = <?php echo json_encode($dataView, 15, 512) ?>;

    // Function to geocode city/country and add marker
    async function addPropertyMarker(property) {
        try {
            // Use Nominatim API for geocoding
            const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(property.location)}&format=json&limit=1`, {
                headers: {
                    'User-Agent': 'YourAppName/1.0 (your.email@example.com)' // Required by Nominatim
                }
            });
            const data = await response.json();

            if (data.length > 0) {
                const { lat, lon } = data[0];
                L.marker([lat, lon])
                    .addTo(map)
                    .bindPopup(`<b>${property.name}</b><br>${property.location}`)
                    .openPopup();

                // Adjust map bounds to include all markers
                map.fitBounds(map.getBounds().extend([lat, lon]));
            }
        } catch (error) {
            console.error('Geocoding error for:', property.location, error);
        }
    }

    // Add markers for all properties
    properties.forEach(property => addPropertyMarker(property));

    // Ensure Livewire re-renders update the map
    Livewire.on('refreshMap', () => {
        map.eachLayer(layer => {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });
        properties.forEach(property => addPropertyMarker(property));
    });
});
</script>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/template/map.blade.php ENDPATH**/ ?>