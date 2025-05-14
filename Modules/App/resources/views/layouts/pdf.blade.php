<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* PDF Styles for Hospitality Templates */
body {
    font-family: 'Inter', Arial, sans-serif;
    font-size: 12pt;
    color: #1f2937;
    background: #fff;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* A4 Size (210mm x 297mm) with PDF margins */
@page {
    size: A4;
    margin: 15mm;
}

/* Page container */
.page {
    width: 180mm;
    min-height: 267mm;
    padding: 15mm;
    margin: 0 auto;
    background: #fff;
    box-sizing: border-box;
    max-width: 100%;
    overflow: hidden;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #097274;
    padding-bottom: 8mm;
    margin-bottom: 8mm;
}

.header-logo img {
    width: 80px;
    height: auto;
    max-width: 100%;
}

.header-info {
    text-align: right;
    font-size: 10pt;
    color: #4b5563;
}

.header-info h1 {
    font-size: 16pt;
    color: #097274;
    margin: 0;
}

/* Guest Details */
.guest-details {
    margin-bottom: 8mm;
}

.guest-details p {
    margin: 2mm 0;
    font-size: 10pt;
    overflow-wrap: break-word;
}

.guest-details strong {
    color: #097274;
}

/* Table (for Invoice, Payment) */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8mm;
    table-layout: fixed;
}

.table th, .table td {
    border: 1px solid #d1d5db;
    padding: 3mm;
    text-align: left;
    font-size: 10pt;
    overflow-wrap: break-word;
}

.table th {
    background: #097274;
    color: #fff;
    font-weight: 600;
}

.table td {
    background: #f8f9fa;
}

.table .total {
    font-weight: bold;
    background: #e5e7eb;
}

/* Column widths for invoice table */
.table.invoice-table th:nth-child(1), .table.invoice-table td:nth-child(1) { width: 50%; }
.table.invoice-table th:nth-child(2), .table.invoice-table td:nth-child(2) { width: 15%; }
.table.invoice-table th:nth-child(3), .table.invoice-table td:nth-child(3) { width: 20%; }
.table.invoice-table th:nth-child(4), .table.invoice-table td:nth-child(4) { width: 15%; }

/* Column widths for payment table */
.table.payment-table th:nth-child(1), .table.payment-table td:nth-child(1) { width: 70%; }
.table.payment-table th:nth-child(2), .table.payment-table td:nth-child(2) { width: 30%; }

/* Booking Details */
.booking-details {
    background: #f8f9fa;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 5mm;
    margin-bottom: 8mm;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.booking-details h2 {
    font-size: 12pt;
    color: #097274;
    margin: 0 0 4mm;
    border-bottom: 1px solid #097274;
    padding-bottom: 2mm;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3mm;
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 10pt;
    margin: 2mm 0;
}

.detail-item i {
    color: #097274;
    font-size: 12pt;
    margin-right: 2mm;
}

.detail-item strong {
    color: #097274;
    margin-right: 1mm;
}

.total-amount {
    background: #e5e7eb;
    padding: 3mm;
    border-radius: 4px;
    font-size: 11pt;
    font-weight: 600;
    text-align: right;
    margin-top: 4mm;
}

.confirmation-message {
    font-size: 10pt;
    line-height: 1.5;
    margin-top: 5mm;
    color: #4b5563;
}

/* Content Section */
.content {
    margin-bottom: 8mm;
}

.content p, .content li {
    font-size: 10pt;
    margin: 2mm 0;
    line-height: 1.5;
    overflow-wrap: break-word;
}

.content h2 {
    font-size: 12pt;
    color: #097274;
    margin: 5mm 0 3mm;
}

/* Footer */
.footer {
    border-top: 2px solid #097274;
    padding-top: 5mm;
    text-align: center;
    font-size: 9pt;
    color: #4b5563;
    margin-top: 10mm;
}

.footer p {
    margin: 1mm 0;
}

/* Utility */
.text-teal {
    color: #097274;
}

.text-bold {
    font-weight: 600;
}

.mt-5 {
    margin-top: 5mm;
}

.mb-5 {
    margin-bottom: 5mm;
}

/* Browser responsiveness */
@media screen {
    .page {
        width: 90vw;
        max-width: 180mm;
        min-height: auto;
        padding: 20px;
        margin: 20px auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table th, .table td {
        font-size: 9pt;
        padding: 2mm;
    }

    .header-logo img {
        width: 60px;
    }

    .header-info h1 {
        font-size: 14pt;
    }

    .booking-details {
        padding: 10px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .total-amount {
        font-size: 10pt;
    }
}
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-logo">
                <img src="{{ current_company()->avatar ? Storage::url('avatars/' . current_company()->avatar) . '?v=' . time() : asset('assets/images/logo/logo-black.png') }}" alt="{{ current_company()->name }} Logo">
            </div>
            <div class="header-info">
                <h1>{{ current_company()->name }}</h1>
                <p>{{ current_company()->address ?? '123 Hospitality Lane, Nairobi, Kenya' }}</p>
                <p>Phone: {{ current_company()->phone ?? '+254 123 456 789' }}</p>
                <p>Email: {{ current_company()->email ?? 'ndako@koverae.com' }}</p>
            </div>
        </div>
        
        @yield('content')

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing {{ current_company()->name }}.</p>
            <p>Contact us at {{ current_company()->phone ?? '+254 123 456 789' }} or {{ current_company()->email ?? 'ndako@koverae.com' }}.</p>
            <p>Powered by <a href="https://ndako.koverae.com/?utm=mail"><b>Ndako</b></a></p>
        </div>
    </div>
</body>
</html>
    