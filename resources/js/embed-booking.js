(function () {
    window.NdakoEmbed = {
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
                        
                        const data = {
                            room_id: document.getElementById('roomId').value,
                            check_in: document.getElementById('checkIn').value,
                            check_out: document.getElementById('checkOut').value,
                            people: document.getElementById('people').value,
                            total_amount: document.getElementById('total_amount').value,
                            name: document.getElementById('name').value,
                            email: document.getElementById('email').value,
                            phone: document.getElementById('phone').value,
                            callback_url: callbackUrl, // optional
                        };
                
                        fetch(`${apiBaseUrl}/embed/confirm-booking`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-API-Key": apiKey,
                                "X-API-Secret": apiSecret,
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                // Optional: trigger Paystack here
                                alert(result.message);
                                window.location.href = result.redirect_url || '/booking-success';
                            } else {
                                alert(result.message || "Booking failed.");
                            }
                        })
                        .catch(err => {
                            console.error("Booking error:", err);
                            alert("Something went wrong.");
                        });
                    }
                });
                
                
                // document.addEventListener('click', function (e) {
                //     if (e.target.id === 'confirmBookingBtn') {
                //         const roomId = localStorage.getItem('selectedRoom');
                //         if (!roomId) {
                //             alert('No room selected.');
                //             return;
                //         }

                //         // Send booking request to API
                //         fetch(`${apiBaseUrl}/confirm-booking`, {
                //             method: 'POST',
                //             headers: {
                //                 'Content-Type': 'application/json',
                //                 'X-API-Key': apiKey,
                //                 'X-API-Secret': apiSecret,
                //             },
                //             body: JSON.stringify({
                //                 room_id: roomId,
                //                 check_in: document.getElementById('checkIn').value,
                //                 check_out: document.getElementById('checkOut').value,
                //                 people: document.getElementById('people').value,
                //             }),
                //         })
                //         .then(response => response.json())
                //         .then(result => {
                //             if (result.success) {
                //                 alert(result.message || 'Booking Confirmed.');
                //             } else {
                //                 alert(result.message || 'An error occurred.');
                //             }
                //         })
                //         .catch(error => {
                //             console.error('Error confirming booking:', error);
                //             alert('An error occurred. Please try again.');
                //         });
                //     }
                // });

            })
            .catch(error => console.error('Error loading form:', error));
        },
    };
})();
