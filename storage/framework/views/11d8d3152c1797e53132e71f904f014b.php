<div>
    <div class="p-4 calendar-container">

        <!-- Success Message -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
            <div class="alert alert-success d-flex align-items-center justify-content-between p-3 fs-5 sticky-top shadow-sm alert-dismissible fade show" role="alert">
                <span class="fs-3"><?php echo e(session('success')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger  d-flex align-items-center justify-content-between p-3 fs-5 sticky-top shadow-sm alert-dismissible fade show" role="alert">
                <span class="fs-3"><?php echo e(session('error')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

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

        // Reinitialize when Livewire updates the component
        document.addEventListener('livewire:load', function () {
            initializeCalendar();
        });
        Livewire.on('calendarUpdated', function() {
            initializeCalendar();
        });

        $wire.on('calendarUpdated', () => {
            initializeCalendar();
        });
        // Livewire.hook('message.processed', (message, component) => {
        //     initializeCalendar();
        // });

        function initializeCalendar() {
            let calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: false,
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
                events: <?php echo json_encode($events ?? [], 15, 512) ?>, // Directly assign JSON data
                eventContent: function(info) {
                    let event = info.event;
                    let statusColor = getStatusColor(event.extendedProps.status);

                    return {
                        html: `<div class="cursor-pointer d-flex justify-content-between fc-event-custom" style=" color: white; padding: 5px; border-radius: 5px;">
                                <div class="text-left">
                                    <span class=""><strong>${event.title} - ${event.extendedProps.unitType}</strong></span>
                                    <br>
                                    <span style="font-size: 12px;">${event.extendedProps.status ?? ''}</span>
                                </div>

                                <div class="text-right">
                                    &nbsp;
                                </div>
                            </div>`
                    };
                },

                eventClick: function(info) {
                    Livewire.dispatch('openModal', {
                        component: 'channelmanager::modal.booking-modal',
                        arguments: { booking: info.event.id }
                    });
                    // alert(`Booking: ${info.event.title}\nStatus: ${info.event.extendedProps?.status ?? 'N/A'}`);
                },

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
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/template/calendar.blade.php ENDPATH**/ ?>