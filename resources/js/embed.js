(function () {
    // Embed script initialization
    window.LanternEmbed = {
        init: function (formContainerId) {
            const container = document.getElementById(formContainerId);

            if (!container) {
                console.error('Form container not found.');
                return;
            }

            // Fetch and load the form
            fetch('http://lantern.koverae.localhost/embed/form')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load form');
                    }
                    return response.text();
                })
                .then(html => {
                    container.innerHTML = html;

                    // Add event listener for form submission
                    document.getElementById('checkAvailabilityForm').addEventListener('submit', async function (e) {
                        e.preventDefault();

                        // Collect form data
                        const checkIn = document.getElementById('checkIn').value;
                        const checkOut = document.getElementById('checkOut').value;
                        const roomType = document.getElementById('roomType').value;
                        const people = document.getElementById('people').value;

                        try {
                            // Send request to the backend
                            const response = await fetch('http://lantern.koverae.localhost/api/check-availability', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ check_in: checkIn, check_out: checkOut, room_type: roomType, people: people }),
                            });

                            if (!response.ok) {
                                if (response.status === 400) {
                                    const errorData = await response.json();
                                    document.getElementById('availabilityResult').textContent = errorData.message;
                                } else {
                                    throw new Error('Something went wrong. Please try again later.');
                                }
                            } else {
                                const result = await response.json();
                                const resultDiv = document.getElementById('availabilityResult');
                                resultDiv.textContent = result.message;
                                resultDiv.style.color = result.available ? 'green' : 'red';
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            document.getElementById('availabilityResult').textContent = 'An error occurred. Please try again later.';
                        }
                    });
                })
                .catch(error => console.error('Error loading form:', error));
        },
    };
})();
