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
            <span class="legend-item" style="background-color: #017E84;"></span> Confirmed
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
