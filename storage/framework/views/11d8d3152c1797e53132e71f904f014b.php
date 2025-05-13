<div class="p-4 bg-white rounded-lg shadow-sm calendar-container">
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
        .rooms-section {
            margin-bottom: 1.5rem;
        }
        .rooms-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .rooms-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        .clear-filter-btn {
            font-size: 0.875rem;
            color: #0E6163;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.15s ease-in-out;
        }
        .clear-filter-btn:hover {
            color: #094445;
        }
        .rooms-container {
            position: relative;
        }
        .rooms-scroll {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scroll-snap-type: x mandatory;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
        }
        .room-card {
            scroll-snap-align: start;
            flex: 0 0 18rem;
            padding: 1rem;
            background-color: #ffffff;
            background-image: linear-gradient(45deg, #f9fafb 25%, transparent 25%, transparent 50%, #f9fafb 50%, #f9fafb 75%, transparent 75%, transparent);
            background-size: 4px 4px;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .room-card.selected {
            border: 2px solid transparent;
            background: linear-gradient(#eff6ff, #eff6ff) padding-box, linear-gradient(45deg, #0E6163, #2c8f91) border-box;
        }
        .room-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .room-card-header h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .status-dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }
        .status-dot.vacant {
            background-color: #0E6163;
        }
        .status-dot.vacant:hover {
            animation: pulse 1.5s infinite;
        }
        .status-dot.occupied {
            background-color: #ef4444;
        }
        .room-type {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #4b5563;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .room-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
        .room-status {
            font-size: 0.75rem;
            font-weight: 500;
        }
        .room-status.available {
            color: #0E6163;
        }
        .room-status.occupied {
            color: #dc2626;
        }
        .room-capacity {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .gradient-overlay-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 1.5rem;
            height: 100%;
            pointer-events: none;
            background: linear-gradient(to right, #ffffff, transparent);
        }
        .gradient-overlay-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 1.5rem;
            height: 100%;
            pointer-events: none;
            background: linear-gradient(to left, #ffffff, transparent);
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        @media (max-width: 640px) {
            .room-card {
                flex: 0 0 16rem;
            }
        }
    </style>
    
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
    <div class="rooms-section">
        <div class="rooms-header">
            <h3>Rooms</h3>
            <!--[if BLOCK]><![endif]--><?php if($selectedUnit): ?>
                <button wire:click="clearUnitFilter" class="clear-filter-btn">Clear Filter</button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div class="rooms-container">
            <div class="rooms-scroll">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div wire:key="unit-<?php echo e($unit->id); ?>" wire:click="selectUnit(<?php echo e($unit->id); ?>)" class="room-card <?php echo e($selectedUnit == $unit->id ? 'selected' : ''); ?>" role="button" aria-label="Select room <?php echo e($unit->name); ?>">
                        <div class="room-card-header">
                            <h4><?php echo e($unit->name); ?></h4>
                            <span class="status-dot <?php echo e($unit->status == 'vacant' ? 'vacant' : 'occupied'); ?>" aria-hidden="true"></span>
                        </div>
                        <p class="room-type"><?php echo e($unit->unitType->name ?? 'N/A'); ?></p>
                        <div class="room-footer">
                            <span class="room-status <?php echo e($unit->status == 'vacant' ? 'vacant' : 'occupied'); ?>"><?php echo e(inverseSlug($unit->status)); ?></span>
                            <span class="room-capacity">Capacity: <?php echo e($unit->capacity ?? 'N/A'); ?> <i class="bi bi-people"></i></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p style="font-size: 0.875rem; color: #4b5563;">No rooms available.</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="gradient-overlay-left"></div>
            <div class="gradient-overlay-right"></div>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="mb-4 calendar-legend">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center;">
                <span class="legend-item" style="background-color: #fbc02d;"></span>
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563;">Pending</span>
            </div>
            <div style="display: flex; align-items: center;">
                <span class="legend-item" style="background-color: #017e84;"></span>
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563;">Confirmed</span>
            </div>
            <div style="display: flex; align-items: center;">
                <span class="legend-item" style="background-color: #1e88e5;"></span>
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563;">Completed</span>
            </div>
            <div style="display: flex; align-items: center;">
                <span class="legend-item" style="background-color: #e53935;"></span>
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563;">Canceled</span>
            </div>
            <div style="display: flex; align-items: center;">
                <span class="legend-item" style="background-color: #757575;"></span>
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563;">Fallback</span>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div id="calendar" class="rounded-lg" wire:ignore></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
    });

    Livewire.on('calendarUpdated', function({ events }) {
        initializeCalendar(events);
    });

    let calendar = null;

    function initializeCalendar(eventsData) {
        let calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        if (calendar) {
            calendar.destroy();
        }

        eventsData = eventsData || <?php echo json_encode($events ?? [], 15, 512) ?>;

        calendar = new FullCalendar.Calendar(calendarEl, {
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
            contentHeight: 'auto'
        });

        calendar.render();
    }

    function getStatusColor(status) {
        console.log('Status:', status);
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
</script><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/template/calendar.blade.php ENDPATH**/ ?>