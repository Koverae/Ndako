<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>

    <style>
        /* General styles */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }
        .icon {
            font-size: 50px;
            margin-bottom: 10px;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeIn 0.8s ease-out forwards;
        }
        .success {
            color: #27ae60;
        }
        .error {
            color: #e74c3c;
        }
        .message {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeIn 1.2s ease-out forwards;
        }
        .details {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1.5s ease-out forwards;
        }
        .button {
            display: inline-block;
            background: #58ACB2;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
            opacity: 0;
            animation: fadeIn 2s ease-out forwards;
        }
        .button:hover {
            background: #005A5F;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    @livewireStyles
    @livewireScripts
</head>
<body>
    
    <div class="container">
        <div id="icon" class="icon">⏳</div>
        <div id="message" class="message">Processing Payment...</div>
        <div id="details" class="details">Please wait while we confirm your transaction.</div>
        <a id="close-btn" class="button" style="display: none;" onclick="window.close()">Close</a>
    </div>

    <script>
        let data = @json($data);
        console.log("DEBUG: Paystack Data:", data);

        let status = data?.status?.toLowerCase() || 'failed';
        let reference = data?.reference || 'N/A';

        let iconElement = document.getElementById("icon");
        let messageElement = document.getElementById("message");
        let detailsElement = document.getElementById("details");
        let closeButton = document.getElementById("close-btn");

        if (window.opener && window.opener.Livewire) {
            if (status === 'success' || status === 'completed') {
                iconElement.innerHTML = "✅";
                iconElement.classList.add("success");
                messageElement.textContent = "Payment Successful!";
                detailsElement.innerHTML = `Your payment was received successfully. <br> Reference: <strong>${reference}</strong>`;
                closeButton.style.display = "inline-block";

                // window.opener.Livewire.dispatch('paymentCompleted', { reference, status: 'success' });
            } else {
                iconElement.innerHTML = "❌";
                iconElement.classList.add("error");
                messageElement.textContent = "Payment Failed!";
                detailsElement.innerHTML = `Oops! Something went wrong. <br> Please try again later. <br> Reference: <strong>${reference}</strong>`;
                closeButton.style.display = "inline-block";

                // window.opener.Livewire.dispatch('paymentCompleted', { reference, status: 'failed' });
            }

            // Wait 3 seconds, then refresh the parent tab and close the popup
            setTimeout(() => {
                window.opener.location.reload();
                window.close();
            }, status === 'success' ? 3000 : 6000); // 3s for success, 5s for failure
        }
    </script>
</body>
</html>
