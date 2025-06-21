<div>
    <div class="p-4 calendar-container">

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="p-3 shadow-sm alert alert-success d-flex align-items-center justify-content-between fs-5 sticky-top alert-dismissible fade show" role="alert">
                <span class="fs-3">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-3 shadow-sm alert alert-danger d-flex align-items-center justify-content-between fs-5 sticky-top alert-dismissible fade show" role="alert">
                <span class="fs-3">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('warning'))
            <div class="p-3 shadow-sm alert alert-warning d-flex align-items-center justify-content-between fs-5 sticky-top alert-dismissible fade show" role="alert">
                <span class="fs-3">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="calendar-legend">
            <span class="legend-item" style="background-color: #fbc02d;"></span> Pending
            <span class="legend-item" style="background-color: #017E84;"></span> Confirmed t
            <span class="legend-item" style="background-color: #1e88e5;"></span> Completed
            <span class="legend-item" style="background-color: #e53935;"></span> Canceled
            <span class="legend-item" style="background-color: #757575;"></span> Fallback
        </div>
        <div id="calendar"></div>
    </div>
</div>

<script>

        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();
        });

        // document.addEventListener('livewire:load', function () {
        //     initializeCalendar();
        // });

        Livewire.on('calendarUpdated', function() {
            setTimeout(() => initializeCalendar(), 100); // Small delay to allow Livewire to update the DOM
        });
        // Livewire.on('calendarUpdated', ({ $events }) => {
        //     setTimeout(() => initializeCalendar($events), 100); // Small delay to allow Livewire to update the DOM
        // });

        function initializeCalendar(eventsData = null) {
            let calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            // Use the provided data, or fall back to the initial dataset
            if (!eventsData) {
                eventsData = @json($events ?? []);
            }

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true, // Enable editing
                selectable: true,
                select: function(info) {
                    // Send selected date range to Livewire and open the modal
                    Livewire.dispatch('openModal', {
                        component: 'channelmanager::modal.add-booking-modal',
                        arguments: {
                            startDate: info.startStr,
                            endDate: info.endStr
                        }
                    });
                },
                events: eventsData, // Directly assign JSON data
                timeZone: 'local', // Forces local timezone
                eventDrop: function(info) {
                    let newStart = info.event.start ? new Date(info.event.start) : null;
                    let newEnd = info.event.end.toISOString();

                    if (newStart) {
                        newStart.setDate(newStart.getDate() + 1); // Fix off-by-one issue
                        newStart = newStart.toISOString();
                    }

                    Livewire.dispatch('updateBookingDate', {
                        bookingId: info.event.id,
                        start: newStart,
                        end: newEnd
                    });
                },

                eventResize: function(info) {
                    let newEnd = new Date(info.event.end);
                    let newStart = info.event.start.toISOString();

                    newEnd.setDate(newEnd.getDate() + 1); // Fix off-by-one issue
                    newEnd = newEnd.toISOString();

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
                        <span>Unit: ${event.title} - ${event.extendedProps.unitType}</span><br>
                        <span>Stay: ${formatDate(event.start)} ~ ${formatDate(event.end)}</span><br />
                        <span>Status: ${event.extendedProps.status}</span>
                    `;
                    document.body.appendChild(tooltip);

                    tooltip.style.left = `${info.jsEvent.pageX + 10}px`;
                    tooltip.style.top = `${info.jsEvent.pageY + 10}px`;

                    info.el.setAttribute('data-tooltip-id', event.id);
                },
                eventMouseLeave: function(info) {
                    let tooltip = document.querySelector(`.calendar-tooltip`);
                    if (tooltip) tooltip.remove();
                },

                eventContent: function(info) {
                    let event = info.event;
                    let statusColor = getStatusColor(event.extendedProps.status);

                    return {
                        html: `<div class="d-flex justify-content-between fc-event-custom" style=" color: white; padding: 5px; border-radius: 5px;">
                                <div class="text-left">
                                    <span class="cursor-pointer" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.booking-modal', arguments: {booking: ${event.id}} })"><strong>${event.title} - ${event.extendedProps.unitType}</strong></span>
                                    <br>
                                    <span style="font-size: 12px;">${event.extendedProps.status ?? ''}</span>
                                </div>

                                <div class="text-right cursor-pointer">
                                    <span class="mb-2" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: ${event.id}} })">
                                        <i class="fas fa-user-cog fs-2" style="color: #fff;"></i>
                                    </span>
                                    <br />
                                    <span class="bg-white fs-6 text-primary badge rounded-pill">${event.extendedProps.channel}</span>
                                </div>
                            </div>`
                    };
                },
                // eventClick: function(info) {
                //     Livewire.dispatch('openModal', {
                //         component: 'channelmanager::modal.booking-modal',
                //         arguments: { booking: info.event.id }
                //     });
                //     // alert(`Booking: ${info.event.title}\nStatus: ${info.event.extendedProps?.status ?? 'N/A'}`);
                // },

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                // select: function (info) {
                //     Livewire.emit('bookingSelected', info.startStr, info.endStr);
                // }
            });

            calendar.render();
        }

        // Function to get status color
        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'pending':
                    return '#fbc02d'; // Yellow
                case 'confirmed':
                    return '#017E84'; // Green
                case 'completed':
                    return '#1e88e5'; // Blue
                case 'canceled':
                    return '#e53935'; // Red
                default:
                    return '#757575'; // Gray (Fallback)
            }
        }
</script>



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