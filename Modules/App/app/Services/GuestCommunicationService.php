<?php

namespace Modules\App\Services;

use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class GuestCommunicationService{

    
    /**
     * Load available email templates.
     *
     * @return array List of template arrays.
     */
    public function getTemplates(): array
    {
        return [
            [
                'id' => 1,
                'apply_to' => 'booking',
                'name' => 'Booking Confirmation',
                'subject' => 'Booking Confirmed: Ref {reference} at {property_name}',
                'content' => "Hi {guest_name},<br>Your booking {reference} at {property_name} from {check_in} to {check_out} is confirmed.<br>Total Amount: <b>{total_amount}</b>.<br>We look forward to hosting you!<br><br>--{company_name} Team",
            ],
            [
                'id' => 2,
                'apply_to' => 'booking',
                'name' => 'Pre-Arrival Welcome Email',
                'subject' => 'Get Ready for Your Stay at {property_name}!',
                'content' => "Hi {guest_name},<br>Your stay is just around the corner!<br>Check-in Date: <b>{check_in}</b><br>Need help? Contact us anytime at {company_phone}.<br><br>--{company_name} Team",
            ],
            [
                'id' => 3,
                'apply_to' => 'booking',
                'name' => 'Check-In Instructions',
                'subject' => 'Your Check-In Details for {property_name}',
                'content' => "Hi {guest_name},<br>Welcome! Here's everything you need for check-in:<br>Room Number: {room_number}<br>Arrival Date: {check_in}<br><br>See you soon!<br>--{company_name}",
            ],
            [
                'id' => 20,
                'apply_to' => 'booking',
                'name' => 'Mid-Stay Check-In',
                'subject' => 'How is Your Stay So Far at {property_name}?',
                'content' => "Hi {guest_name},<br>We hope you're enjoying your stay. If you need anything, we're here for you.<br><br>--{company_name} Team",
            ],
            [
                'id' => 4,
                'apply_to' => 'booking',
                'name' => 'Check-Out Reminder',
                'subject' => 'Check-Out Reminder for Your Stay at {property_name}',
                'content' => "Hi {guest_name},<br>This is a kind reminder that your check-out is scheduled for {check_out}.<br>We hope you had a great stay!<br><br>--{company_name}",
            ],
            [
                'id' => 5,
                'apply_to' => 'booking',
                'name' => 'Cancellation Confirmation',
                'subject' => 'Your Booking {reference} has been Cancelled',
                'content' => "Hi {guest_name},<br>Your reservation {reference} has been cancelled. If you have questions about the refund, reach out to us.<br><br>--{company_name}",
            ],
            [
                'id' => 6,
                'apply_to' => 'booking',
                'name' => 'Reservation Update Confirmation',
                'subject' => 'Your Booking {reference} has been Updated',
                'content' => "Hi {guest_name},<br>Your booking has been updated with the latest details. Please check your dashboard for more info.<br><br>--{company_name}",
            ],
            [
                'id' => 7,
                'apply_to' => 'feedback',
                'name' => 'We\'d Love Your Feedback',
                'subject' => 'How Was Your Stay at {property_name}?',
                'content' => "Hi {guest_name},<br>We hope you enjoyed your stay! Could you take a minute to leave us a review?<br>Your feedback helps us grow.<br><br>--{company_name}",
            ],
            [
                'id' => 8,
                'apply_to' => 'promotion',
                'name' => 'Special Offer Just for You',
                'subject' => 'Exclusive Offer for Your Next Stay at {property_name}',
                'content' => "Hi {guest_name},<br>We’d love to host you again. Here's a <b>{discount}% discount</b> for your next visit!<br><br>--{company_name}",
            ],
            [
                'id' => 9,
                'apply_to' => 'birthday',
                'name' => 'Happy Birthday',
                'subject' => '🎉 Happy Birthday, {guest_name}!',
                'content' => "Hi {guest_name},<br>We at {company_name} wish you a wonderful birthday!<br>Here’s a small gift for your next stay.<br><br>🎁 Enjoy!",
            ],
            [
                'id' => 10,
                'apply_to' => 'payment',
                'name' => 'Payment Confirmation',
                'subject' => 'Payment Received for Booking {reference}',
                'content' => "Hi {guest_name},<br>We’ve received your payment of <b>{total_amount}</b> for booking {reference}.<br>Thank you!<br><br>--{company_name}",
            ],
            [
                'id' => 11,
                'apply_to' => 'invoice',
                'name' => 'Booking Invoice',
                'subject' => 'Invoice for Booking {reference} at {property_name}',
                'content' => "Hi {guest_name},<br>Attached is your invoice for your recent stay. Amount: <b>{total_amount}</b>.<br><br>--{company_name}",
            ],
            [
                'id' => 12,
                'apply_to' => 'invoice',
                'name' => 'Invoice Payment Reminder',
                'subject' => 'Reminder: Invoice Due for Booking {reference}',
                'content' => "Hi {guest_name},<br>This is a reminder to complete the payment of <b>{total_amount}</b> for your stay.<br><br>--{company_name}",
            ],
            [
                'id' => 13,
                'apply_to' => 'invoice',
                'name' => 'Overdue Payment Notice',
                'subject' => 'Action Required: Overdue Payment for Booking {reference}',
                'content' => "Hi {guest_name},<br>Your payment for booking {reference} is overdue. Please clear it to avoid penalties.<br><br>--{company_name}",
            ],
            [
                'id' => 14,
                'apply_to' => 'refund',
                'name' => 'Refund Processed',
                'subject' => 'Refund Issued for Booking {reference}',
                'content' => "Hi {guest_name},<br>We’ve processed your refund of <b>{refund_amount}</b> for booking {reference}.<br>Expect to see it within 3-5 business days.<br><br>--{company_name}",
            ],
            [
                'id' => 15,
                'apply_to' => 'maintenance',
                'name' => 'Maintenance Report Submitted',
                'subject' => 'Maintenance Request for Room {room_number}',
                'content' => "Hi Team,<br>A new maintenance issue has been reported in Room {room_number}.<br>Please review and act accordingly.<br><br>--Ndako System",
            ],
            [
                'id' => 16,
                'apply_to' => 'maintenance',
                'name' => 'Maintenance Request Update',
                'subject' => 'Update on Your Maintenance Request (Ref {reference})',
                'content' => "Hi {guest_name},<br>We wanted to update you on the status of your request. It is now marked as {status}.<br><br>--{company_name}",
            ],
            [
                'id' => 17,
                'apply_to' => 'housekeeping',
                'name' => 'Housekeeping Notification',
                'subject' => 'Scheduled Housekeeping for Room {room_number}',
                'content' => "Hi Team,<br>Housekeeping is scheduled for Room {room_number} on {scheduled_date}.<br><br>--Ndako System",
            ],
            [
                'id' => 18,
                'apply_to' => 'lease',
                'name' => 'Lease Expiry Reminder',
                'subject' => 'Your Lease Expires on {lease_end_date}',
                'content' => "Hi {guest_name},<br>Your lease at {property_name} will end on {lease_end_date}.<br>Please contact us if you'd like to renew.<br><br>--{company_name}",
            ],
            [
                'id' => 19,
                'apply_to' => 'booking',
                'name' => 'New Booking Staff Notification',
                'subject' => 'New Booking Received: {reference}',
                'content' => "Hi Team,<br>A new booking has been made for {property_name} from {check_in} to {check_out}.<br>Please prepare accordingly.<br><br>--Ndako System",
            ],
        ];
    }


    /**
     * Generate a PDF attachment based on the template's apply_to type.
     *
     * @param array $data Data for PDF rendering (e.g., total_amount, guest_name).
     * @return string|null Path to the generated PDF or null if no attachment.
     */
    public function generatePdfAttachment(array $data, $template, $templateId): ?string
    {
        $applyTo = $template->apply_to;
        $templateMap = [
            'invoice' => 'app::pdf.templates.invoice',
            'payment' => 'app::pdf.templates.payment',
        ];

        if (!isset($templateMap[$applyTo])) {
            Log::info('No PDF template for apply_to', ['apply_to' => $applyTo]);
            return null;
        }

        try {
            // Prepare data for PDF
            $pdfData = array_merge([
                'guest_name' => $this->contact->name ?? 'Guest',
                'company_name' => current_company()->name ?? 'Company',
                // 'date' => ,
            ], $data);
            Log::info('PDF data prepared', ['pdfData' => $pdfData]);

            // Generate PDF
            $pdf = Pdf::loadView($templateMap[$applyTo], $pdfData);
            $filename = "attachments/{$applyTo}_{$templateId}_" . time() . '.pdf';
            $path = storage_path("app/public/{$filename}");
            $pdf->save($path);

            Log::info('PDF generated successfully', ['path' => $path, 'apply_to' => $applyTo]);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF', [
                'apply_to' => $applyTo,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

}