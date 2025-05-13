<div>
    <style>
        .calendar-legend .legend-item {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        .calendar-tooltip {
            position: fixed;
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-width: 300px;
            font-size: 12px;
            color: #374151;
        }
        .fc-event-custom {
            background-color: var(--status-color, #757575) !important;
            border: none !important;
        }
        .scrollbar-thin {
            scrollbar-width: thin;
        }
        .scrollbar-thumb-gray-300 {
            scrollbar-color: #d1d5db #f3f4f6;
        }
    </style>
    <div class="p-4 bg-white rounded-lg shadow-sm calendar-container">
        <!-- Alerts -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
            <div class="p-3 mb-4 alert alert-success d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
                <span class="text-lg"><?php echo e(session('success')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php if(session()->has('error')): ?>
            <div class="p-3 mb-4 alert alert-danger d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
                <span class="text-lg"><?php echo e(session('error')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php if(session()->has('warning')): ?>
            <div class="p-3 mb-4 alert alert-warning d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
                <span class="text-lg"><?php echo e(session('warning')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Property Units Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xl font-semibold text-gray-800">Rooms</h3>
                <!--[if BLOCK]><![endif]--><?php if($selectedUnit): ?>
                    <button wire:click="clearUnitFilter" class="text-sm text-blue-600 transition duration-150 hover:text-blue-800">Clear Filter</button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="relative">
                <div class="flex pb-2 space-x-4 overflow-x-auto snap-x scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div wire:key="unit-<?php echo e($unit->id); ?>" wire:click="selectUnit(<?php echo e($unit->id); ?>)" class="snap-start flex-none w-72 p-4 bg-white rounded-xl shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition-all duration-200 <?php echo e($selectedUnit == $unit->id ? 'border-2 border-blue-500 bg-blue-50' : 'border border-gray-200'); ?>" role="button" aria-label="Select room <?php echo e($unit->name); ?>">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-900 truncate"><?php echo e($unit->name); ?></h4>
                                <span class="w-3 h-3 rounded-full <?php echo e($unit->status == 'available' ? 'bg-green-500' : 'bg-red-500'); ?>" aria-hidden="true"></span>
                            </div>
                            <p class="mt-2 text-xs text-gray-600 truncate"><?php echo e($unit->unitType->name ?? 'N/A'); ?></p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs <?php echo e($unit->status == 'available' ? 'text-green-600' : 'text-red-600'); ?> font-medium"><?php echo e(ucfirst($unit->status)); ?></span>
                                <span class="text-xs text-gray-500">Capacity: <?php echo e($unit->capacity ?? 'N/A'); ?> <i class="bi bi-people"></i></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-600">No rooms available.</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="absolute top-0 left-0 w-6 h-full pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
                <div class="absolute top-0 right-0 w-6 h-full pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
            </div>
        </div>

        <!-- Calendar Legend -->
        <div class="flex flex-wrap gap-4 mb-4 calendar-legend">
            <div class="flex items-center">
                <span class="bg-yellow-500 legend-item"></span>
                <span class="ml-2 text-sm text-gray-700">Pending</span>
            </div>
            <div class="flex items-center">
                <span class="bg-teal-600 legend-item"></span>
                <span class="ml-2 text-sm text-gray-700">Confirmed</span>
            </div>
            <div class="flex items-center">
                <span class="bg-blue-600 legend-item"></span>
                <span class="ml-2 text-sm text-gray-700">Completed</span>
            </div>
            <div class="flex items-center">
                <span class="bg-red-600 legend-item"></span>
                <span class="ml-2 text-sm text-gray-700">Canceled</span>
            </div>
            <div class="flex items-center">
                <span class="bg-gray-500 legend-item"></span>
                <span class="ml-2 text-sm text-gray-700">Fallback</span>
            </div>
        </div>

        <!-- Calendar -->
        <div id="calendar" class="rounded-lg"></div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
    });

    Livewire.on('calendarUpdated', function({ events }) {
        initializeCalendar(events);
    });

    function initializeCalendar(eventsData) {
        let calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        eventsData = eventsData || <?php echo json_encode($events ?? [], 15, 512) ?>;

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: '<?php echo e($options['initialView']); ?>',
            editable: <?php echo e($options['editable'] ? 'true' : 'false'); ?>,
            selectable: <?php echo e($options['selectable'] ? 'true' : 'false'); ?>,
            select: function(info) {
                Livewire.dispatch('openModal', {
                    component: 'channelmanager::modal.add-booking-modal',
                    arguments: {
                        startDate: info.startStr,
                        endDate: info.endStr
                    }
                });
            },
            events: eventsData,
            timeZone: 'local',
            eventDrop: function(info) {
                let newStart = info.event.start ? info.event.start.toISOString() : null;
                let newEnd = info.event.end ? info.event.end.toISOString() : null;

                Livewire.dispatch('updateBookingDate', {
                    bookingId: info.event.id,
                    start: newStart,
                    end: newEnd
                });
            },
            eventResize: function(info) {
                let newStart = info.event.start ? info.event.start.toISOString() : null;
                let newEnd = info.event.end ? info.event.end.toISOString() : null;

                Livewire.dispatch('updateBookingDate', {
                    bookingId: info.event.id,
                    start: newStart,
                    end: newEnd
                });
            },
            eventMouseEnter: function(info) {
                let event = info.event;
                let tooltip = document.createElement('div');
                tooltip.className = 'calendar-tooltip';
                tooltip.innerHTML = `
                    <strong>${event.extendedProps.reference}</strong><br>
                    <span>Guest: ${event.extendedProps.guest}</span><br>
                    <span>Room: ${event.extendedProps.room} - ${event.extendedProps.unitType}</span><br>
                    <span>Stay: ${formatDate(event.start)} ~ ${formatDate(event.end)}</span><br>
                    <span>Status: ${event.extendedProps.status}</span>
                `;
                document.body.appendChild(tooltip);

                let x = info.jsEvent.pageX + 10;
                let y = info.jsEvent.pageY + 10;
                if (x + 300 > window.innerWidth) x = info.jsEvent.pageX - 320;
                if (y + 100 > window.innerHeight) y = info.jsEvent.pageY - 100;
                tooltip.style.left = `${x}px`;
                tooltip.style.top = `${y}px`;

                info.el.setAttribute('data-tooltip-id', event.id);
            },
            eventMouseLeave: function(info) {
                let tooltip = document.querySelector('.calendar-tooltip');
                if (tooltip) tooltip.remove();
            },
            eventContent: function(info) {
                let event = info.event;
                let statusColor = getStatusColor(event.extendedProps.status);

                return {
                    html: `
                        <div class="d-flex justify-content-between fc-event-custom animate__animated animate__fadeIn" style="--status-color: ${statusColor}; color: white; padding: 5px; border-radius: 5px;">
                            <div class="text-left">
                                <span class="cursor-pointer" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.booking-modal', arguments: {booking: ${event.id}}})">
                                    <strong>${event.title} - ${event.extendedProps.unitType}</strong>
                                </span>
                                <br>
                                <span style="font-size: 12px;">${event.extendedProps.status}</span>
                            </div>
                            <div class="text-right cursor-pointer">
                                <span class="mb-2" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: ${event.id}}})">
                                    <i class="fas fa-user-cog fs-2" style="color: #fff;"></i>
                                </span>
                                <br>
                                <span class="bg-white fs-6 text-primary badge rounded-pill">${event.extendedProps.channel}</span>
                            </div>
                        </div>`
                };
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
            height: 'auto',
            contentHeight: 'auto',
            responsive: true
        });

        calendar.render();
    }

    function getStatusColor(status) {
        console.log('Status:', status); // Debug status value
        switch (status.toLowerCase()) {
            case 'pending':
                return '#fbc02d';
            case 'confirmed':
                return '#017e84';
            case 'completed':
                return '#1e88e5';
            case 'canceled':
                return '#e53935';
            default:
                return '#757575';
        }
    }

    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
</script>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/template/calendar.blade.php ENDPATH**/ ?>