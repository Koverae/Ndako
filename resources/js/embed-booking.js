(function () {
    window.LanternEmbed = {
        init: function (formContainerId, apiBaseUrl, publicKey, secretKey, successUrl) {
            // Fetch the API configuration from the backend

            const apiKey = publicKey;  // Public API key for frontend
            const apiSecret = secretKey;  // Secret API key for backend use
            const callbackUrl = successUrl;

            const container = document.getElementById(formContainerId);
            if (!container) {
                console.error('Form container not found.');
                return;
            }

            // Fetch and load the form
            fetch(`${apiBaseUrl}/embed/form`, {
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
                        const response = await fetch(`${apiBaseUrl}/check-availability`, {
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
                            fetch(`${apiBaseUrl}/available-rooms-html`, {
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
                        fetch(`${apiBaseUrl}/embed/rooms/${roomId}`, {
                            headers: {
                                'X-API-Key': apiKey,
                                'X-API-Secret': apiSecret,
                            }
                        })
                        .then(response => response.json())
                        .then(roomData => {
                            const roomId = roomData.id;
                            
                            const url = new URL(`${apiBaseUrl}/confirm-booking-html/${roomId}`);
                            url.searchParams.append('check_in', checkIn);
                            url.searchParams.append('check_out', checkOut);
                            url.searchParams.append('callback_url', callbackUrl);
                            
                            fetch(url.toString(), {
                                headers: {
                                    'X-API-Key': apiKey,
                                    'X-API-Secret': apiSecret,
                                },
                            })
                            .then(response => response.text())
                            .then(html => {
                                document.getElementById('checkoutSection').innerHTML = html;
                            })
                            .catch(error => {
                                console.error('Error loading room details:', error);
                                alert('Error loading room details html:', error);
                            });
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
                        fetch(`${apiBaseUrl}/embed/confirm-booking`, {
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

                
                // document.getElementById('bookingForm').addEventListener('submit', function (e) {
                //     e.preventDefault();

                //     const form = e.target;
                //     const formData = {
                //         name: form.name.value,
                //         email: form.email.value,
                //         phone: form.phone.value,
                //         room_id: form.room_id.value,
                //         check_in: form.check_in.value,
                //         check_out: form.check_out.value,
                //         total_price: form.total_price.value,
                //         // callback: callbackUrl,
                //     };
                    
                //     const endpoint = `{{ route('api.initiate-booking') }}?callback=${encodeURIComponent(callbackUrl)}`;

                //     fetch(endpoint, {
                //         method: "POST",
                //         headers: {
                //             "Content-Type": "application/json",
                //             "X-API-Key": apiKey,
                //             "X-API-Secret": apiSecret,
                //         },
                //         body: JSON.stringify(formData),
                //     })
                //     .then(response => response.json())
                //     .then(data => {
                //         if (data.success) {
                //             alert("Booking initiated!");
                //             window.location.href = formData.success_url || '/booking-success';
                //             // Later: Trigger Paystack popup here
                //         } else {
                //             alert(data.message || "Something went wrong.");
                //         }
                //     })
                //     .catch(err => {
                //         console.error("Error:", err);
                //         alert("Failed to initiate booking. Please try again.");
                //     });
                // });

            })
            .catch(error => console.error('Error loading form:', error));
        },
    };
})();
