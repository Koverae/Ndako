(function () {
    window.LanternEmbed = {
        init: function (formContainerId, apiBaseUrl) {
            // Fetch the API configuration from the backend
            fetch(`${apiBaseUrl}/api/get-embed-config`)
                .then(response => response.json())
                .then(apiConfig => {
                    const apiKey = apiConfig.publicKey;  // Public API key for frontend
                    const apiSecret = apiConfig.secretKey;  // Secret API key for backend use

                    const container = document.getElementById(formContainerId);
                    if (!container) {
                        console.error('Form container not found.');
                        return;
                    }

                    // Fetch and load the form
                    fetch(`${apiBaseUrl}/api/embed/form`, {
                        headers: {
                            'X-API-Key': apiKey,
                            'X-API-Secret': apiSecret,
                        },
                    })
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;

                        // Event Listener for Form Submission
                        document.getElementById('checkAvailabilityForm').addEventListener('submit', async function (e) {
                            e.preventDefault();

                            const checkIn = document.getElementById('checkIn').value;
                            const checkOut = document.getElementById('checkOut').value;
                            const roomType = document.getElementById('roomType').value;
                            const people = document.getElementById('people').value;

                            try {
                                const response = await fetch(`${apiBaseUrl}/api/check-availability`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-API-Key': apiKey,
                                        'X-API-Secret': apiSecret,
                                    },
                                    body: JSON.stringify({
                                        check_in: checkIn,
                                        check_out: checkOut,
                                        room_type: roomType,
                                        people: people,
                                    }),
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    document.getElementById('availabilityResult').textContent = errorData.message;
                                } else {
                                    const result = await response.json();
                                    fetch(`${apiBaseUrl}/api/available-rooms-html`, {
                                        headers: {
                                            'X-API-Key': apiKey,
                                            'X-API-Secret': apiSecret,
                                        },
                                    })
                                    .then(response => response.text())
                                    .then(html => {
                                        document.getElementById('availableRooms').innerHTML = html;
                                    })
                                    .catch(error => {
                                        console.error('Error loading available rooms:', error);
                                        document.getElementById('availabilityResult').textContent = 'Failed to load available rooms.';
                                    });
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                document.getElementById('availabilityResult').textContent = 'An error occurred. Please try again later.';
                            }
                        });

                        // 🔥 NEW: Event Listener for Room Selection
                        document.addEventListener('click', function (e) {
                            if (e.target.classList.contains('select-room-btn')) {
                                const roomId = e.target.getAttribute('data-room-id');
                                const checkIn = document.getElementById('checkIn').value;
                                const checkOut = document.getElementById('checkOut').value;

                                // Store selected room in localStorage
                                localStorage.setItem('selectedRoom', roomId);

                                // Fetch room details from the API
                                fetch(`${apiBaseUrl}/api/rooms/${roomId}`, {
                                    headers: {
                                        'X-API-Key': apiKey,
                                        'X-API-Secret': apiSecret,
                                    }
                                })
                                .then(response => response.json())
                                .then(roomData => {
                                    if (roomData.name) {
                                        // Display checkout UI with room details
                                        document.getElementById('checkoutSection').innerHTML = `
                                            <h3>Confirm Booking</h3>
                                            <p><strong>Room Name:</strong> ${roomData.name} ~ ${roomData.type}</p>
                                            <p><strong>Price:</strong> ${roomData.price}</p>
                                            <p><strong>Details:</strong> ${roomData.details}</p>
                                            <p><strong>Stay:</strong> ${checkIn} ~ ${checkOut}</p>
                                            <button id="confirmBookingBtn">Confirm Booking</button>
                                        `;
                                    } else {
                                        alert('Room not found.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching room details:', error);
                                    alert('Failed to fetch room details. Please try again later.');
                                });
                            }
                        });

                        // 🔥 NEW: Event Listener for Booking Confirmation
                        document.addEventListener('click', function (e) {
                            if (e.target.id === 'confirmBookingBtn') {
                                const roomId = localStorage.getItem('selectedRoom');
                                if (!roomId) {
                                    alert('No room selected.');
                                    return;
                                }

                                // Send booking request to API
                                fetch(`${apiBaseUrl}/api/confirm-booking`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-API-Key': apiKey,
                                        'X-API-Secret': apiSecret,
                                    },
                                    body: JSON.stringify({
                                        room_id: roomId,
                                        check_in: document.getElementById('checkIn').value,
                                        check_out: document.getElementById('checkOut').value,
                                        people: document.getElementById('people').value,
                                    }),
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        alert(result.message || 'Booking Confirmed.');
                                    } else {
                                        alert(result.message || 'An error occurred.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error confirming booking:', error);
                                    alert('An error occurred. Please try again.');
                                });
                            }
                        });

                    })
                    .catch(error => console.error('Error loading form:', error));
                })
                .catch(error => {
                    console.error('Error fetching API config:', error);
                    alert('Failed to load configuration. Please try again later.');
                });
        },
    };
})();
