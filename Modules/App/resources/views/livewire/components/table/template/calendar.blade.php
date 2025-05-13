<div class="p-4 bg-white rounded-lg shadow-sm calendar-container" wire:key="calendar-container">
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
            font-size: 13px;
            color: #374151;
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .fc-event-custom {
            background-color: var(--status-color, #757575) !important;
            border: none !important;
            border-radius: 6px;
            padding: 8px;
            margin: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            cursor: pointer;
            max-height: 120px;
            overflow: hidden;
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .fc-event-custom:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            border: 2px solid transparent;
            background: linear-gradient(var(--status-color), var(--status-color)) padding-box, linear-gradient(45deg, #0E6163, #3aa8aa) border-box;
        }
        .event-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: white;
            font-size: 13px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
            line-height: 1.3;
        }
        .event-header {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .event-reference {
            font-weight: 600;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .event-status-icon {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: white;
            opacity: 0.8;
        }
        .event-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .event-guest, .event-room, .event-dates {
            font-size: 13px;
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }
        .event-channel {
            background: white;
            color: #1f2937;
            font-size: 12px;
            font-weight: 500;
            padding: 2px 6px;
            border-radius: 10px;
            line-height: 1.2;
        }
        .event-actions {
            display: flex;
            gap: 6px;
        }
        .event-action-icon {
            font-size: 16px;
            color: white;
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }
        .event-action-icon:hover {
            opacity: 1;
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
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .rooms-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .rooms-header-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .clear-filter-btn, .new-booking-btn {
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .clear-filter-btn {
            color: #0E6163;
            background: none;
            border: none;
        }
        .clear-filter-btn:hover {
            color: #094445;
        }
        .new-booking-btn {
            color: white;
            background: linear-gradient(45deg, #0E6163, #2c8f91);
            border: none;
        }
        .new-booking-btn:hover {
            background: linear-gradient(45deg, #094445, #3aa8aa);
            animation: pulse 1.5s infinite;
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
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
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
            font-size: 0.975rem;
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
            font-size: 0.85rem;
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
            font-size: 0.85rem;
            font-weight: 500;
        }
        .room-status.vacant {
            color: #0E6163;
        }
        .room-status.occupied {
            color: #dc2626;
        }
        .room-capacity {
            font-size: 0.80rem;
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
        .fc-button {
            font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s ease !important;
        }
        .fc-button:hover {
            background: linear-gradient(45deg, #0E6163, #2c8f91) !important;
            color: white !important;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @media (max-width: 640px) {
            .room-card {
                flex: 0 0 16rem;
            }
            .rooms-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .rooms-header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            .clear-filter-btn, .new-booking-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
            }
            .fc-event-custom {
                padding: 6px;
                font-size: 11px;
                max-height: 100px;
            }
            .event-reference {
                font-size: 13px;
            }
            .event-guest, .event-room, .event-dates {
                font-size: 11px;
            }
            .event-channel {
                font-size: 10px;
                padding: 1px 4px;
            }
            .event-action-icon {
                font-size: 14px;
            }
            .fc-button {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>

    <!-- Alerts -->
    @if (session()->has('success'))
        <div class="p-3 mb-4 alert alert-success d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
            <span class="text-lg">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-3 mb-4 alert alert-danger d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
            <span class="text-lg">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="p-3 mb-4 alert alert-warning d-flex align-items-center justify-content-between animate__animated animate__fadeIn" role="alert">
            <span class="text-lg">{{ session('warning') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Property Units Section -->
    <div class="rooms-section">
        <div class="rooms-header">
            <h3>Rooms</h3>
            <div class="rooms-header-actions">
                @if($selectedUnit)
                    <button wire:click="clearUnitFilter" class="clear-filter-btn">Clear Filter</button>
                @endif
                
                @role('front-desk')
                
                    @if($units->isNotEmpty())
                    @php
                        $startDate = now();
                        $endDate = now()->addDays(1);
                    @endphp
                    
                    <button
                        onclick="Livewire.dispatch('openModal', {
                            component: 'channelmanager::modal.add-booking-modal',
                            arguments: {
                                startDate: '{{ $startDate->toISOString() }}',
                                endDate: '{{ $endDate->toISOString() }}'
                            }
                        })"
                        class="new-booking-btn"
                    >
                        New Booking
                    </button>
                    @endif
                @endrole
            </div>
        </div>
        <div class="rooms-container">
            <div class="rooms-scroll">
                @forelse($units as $unit)
                    <div wire:key="unit-{{ $unit->id }}" wire:click="selectUnit({{ $unit->id }})" class="room-card {{ $selectedUnit == $unit->id ? 'selected' : '' }}" role="button" aria-label="Select room {{ $unit->name }}">
                        <div class="room-card-header">
                            <h4>{{ $unit->name }}</h4>
                            <span class="status-dot {{ $unit->status == 'vacant' ? 'vacant' : 'occupied' }}" aria-hidden="true"></span>
                        </div>
                        <p class="room-type">{{ $unit->unitType->name ?? 'N/A' }}</p>
                        <div class="room-footer">
                            <span class="room-status {{ $unit->status == 'vacant' ? 'vacant' : 'occupied' }}">{{ inverseSlug($unit->status) }}</span>
                            <span class="room-capacity">Capacity: {{ $unit->capacity ?? 'N/A' }} <i class="bi bi-people"></i></span>
                        </div>
                    </div>
                @empty
                    <p style="font-size: 0.875rem; color: #4b5563;">No rooms available.</p>
                @endforelse
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
    <div id="calendar" class="rounded-lg" style="min-height: 500px;"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.debug('DOM loaded, initializing calendar');
        initializeCalendar();
    });

    Livewire.on('calendarUpdated', function() {
            setTimeout(() => initializeCalendar(), 100); // Small delay to allow Livewire to update the DOM
        });
    let calendar = null;

    function initializeCalendar(eventsData = @json($events ?? [])) {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error('Calendar element not found');
            return;
        }

        if (calendar) {
            console.debug('Destroying existing calendar');
            calendar.destroy();
        }

        console.debug('Initializing calendar with events:', eventsData);

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: '{{ $options['initialView'] }}',
            editable: {{ $options['editable'] ? 'true' : 'false' }},
            selectable: {{ $options['selectable'] ? 'true' : 'false' }},
            select: function(info) {
                console.debug('Calendar select:', info);
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
                console.debug('Event dropped:', info.event);
                const newStart = info.event.start ? info.event.start.toISOString() : null;
                const newEnd = info.event.end ? info.event.end.toISOString() : null;

                Livewire.dispatch('updateBookingDate', {
                    bookingId: info.event.id,
                    start: newStart,
                    end: newEnd
                });
            },
            eventResize: function(info) {
                console.debug('Event resized:', info.event);
                const newStart = info.event.start ? info.event.start.toISOString() : null;
                const newEnd = info.event.end ? info.event.end.toISOString() : null;

                Livewire.dispatch('updateBookingDate', {
                    bookingId: info.event.id,
                    start: newStart,
                    end: newEnd
                });
            },
            eventMouseEnter: function(info) {
                console.debug('Event mouse enter:', info.event);
                const event = info.event;
                const tooltip = document.createElement('div');
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
                console.debug('Event mouse leave:', info.event);
                const tooltip = document.querySelector('.calendar-tooltip');
                if (tooltip) tooltip.remove();
            },
            eventContent: function(info) {
                console.debug('Rendering event content:', info.event);
                const event = info.event;
                const statusColor = getStatusColor(event.extendedProps.status);

                return {
                    html: `
                        <div class="fc-event-custom animate__animated animate__fadeIn" style="--status-color: ${statusColor};">
                            <div class="event-content">
                                <div class="event-header">
                                    <span class="cursor-pointer event-reference" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.booking-modal', arguments: {booking: ${event.id}}})">
                                        ${event.extendedProps.reference}
                                    </span>
                                    <span class="event-status-icon"></span>
                                </div>
                                <div class="event-details">
                                    <span class="event-guest">${event.extendedProps.guest}</span>
                                    <span class="event-room">${event.extendedProps.room} - ${event.extendedProps.unitType}</span>
                                    <span class="event-dates">${formatDate(event.start)} ~ ${formatDate(event.end)}</span>
                                </div>
                                <div class="event-footer">
                                    <span class="event-channel">${event.extendedProps.channel}</span>
                                    <div class="event-actions">
                                        <i class="cursor-pointer fas fa-user-cog event-action-icon" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: ${event.id}}})"></i>
                                    </div>
                                </div>
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
        console.debug('Calendar rendered with events:', calendar.getEvents());
    }

    Livewire.on('calendarUpdated', function(data) {
        console.log('Received calendarUpdated event with data:', data);
        const events = data.events || [];
        console.debug('Extracted events:', events);

        if (!calendar) {
            console.warn('Calendar not initialized, reinitializing');
            initializeCalendar(events);
            return;
        }

        if (!Array.isArray(events)) {
            console.error('Invalid events data:', events);
            initializeCalendar([]);
            return;
        }

        console.debug('Reinitializing calendar with new events');
        initializeCalendar(events);
    });

    function getStatusColor(status) {
        console.debug('Getting status color for:', status);
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