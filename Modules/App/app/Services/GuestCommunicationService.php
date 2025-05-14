<?php

namespace Modules\App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\App\Emails\Template;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\ChannelManager\Models\Guest\Guest;

class GuestCommunicationService{


    /**
     * Initialize the modal with template and model data, replacing placeholders.
     *
     * @param int $templateId ID of the selected email template.
     * @param mixed $model Model data (e.g., booking, invoice).
     * @param array $subjectReplace Values to replace in subject.
     * @param array $contentReplace Values to replace in content.
     * @param array $data Additional data for PDF generation (e.g., total_amount, guest_name).
     */

    public function initiateTemplate(
        $templateId,
        $model,
        $subjectReplace,
        $contentReplace,
        $data
    ){

        // Ensure $model is an array
        $model = is_array($model) ? $model : [];
        Log::info('Normalized model', ['model' => $model]);

        $templates = $this->getTemplates();

        // Find selected template
        $selected = collect($templates)->firstWhere('id', $templateId);
        if (!$selected) {
            Log::warning('Invalid template ID provided', ['template_id' => $templateId]);
            return false;
        }

        $template = (object) $selected;
        Log::info('Template selected', ['template' => $selected]);

        // Load guest/contact info
        $guestId = null;
        if (isset($model['guest_id']) && is_scalar($model['guest_id'])) {
            $guestId = (int) $model['guest_id'];
        } else {
            Log::warning('Invalid or missing guest_id', ['model' => $model]);
        }

        $contact = $guestId ? Guest::find($guestId) : null;
        if (!$contact && $guestId) {
            Log::warning('Guest not found', ['guest_id' => $guestId]);
        }

        // Set recipient emails
        $recipientField = $template->recipientEmails ?? [];
        $recipientEmails = array_filter(array_merge(
            explode(',', $recipientField),
            [$contact ? $contact->email : null]
        ));
        Log::info('Recipient emails set', ['recipient_emails' => $recipientEmails]);

        // Replace placeholders in subject and content
        $subject = str_replace($template->subjectSearch, $subjectReplace, $template->subject);
        $rawContent = str_replace($template->contentSearch, $contentReplace, $template->content);
        $content = preg_replace('/\{(.*?)\}/', '<b>{$1}</b>', $rawContent);


        // Generate PDF attachment if applicable
        $attachment = $this->generatePdfAttachment($data, $template, $contact);
        Log::info('Mount completed', ['attachment' => $attachment]);

        // Send Email
        $this->sendEmail(
            $recipientEmails,
            $template,
            $subject,
            $content,
            $attachment
        );

    }

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
                'apply_to' => 'booking-confirmation',
                'name' => 'Booking Confirmation',
                'subject' => 'Booking Confirmed: Ref {reference} at {property_name}',
                'subjectSearch' => ['{reference}', '{property_name}'],
                'content' => "Hi {guest_name},<br>Your booking {reference} at {property_name} from {check_in} to {check_out} is confirmed.<br>Total Amount: <b>{total_amount}</b>.<br>We look forward to hosting you!<br><br>--{company_name} Team",
                'contentSearch' => ['{guest_name}', '{property_name}', '{check_in}', '{check_out}', '{total_amount}', '{company_name}'],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 2,
                'apply_to' => 'booking',
                'name' => 'Pre-Arrival Welcome Email',
                'subject' => 'Get Ready for Your Stay at {property_name}!',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Your stay is just around the corner!<br>Check-in Date: <b>{check_in}</b><br>Need help? Contact us anytime at {company_phone}.<br><br>--{company_name} Team",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 3,
                'apply_to' => 'booking',
                'name' => 'Check-In Instructions',
                'subject' => 'Your Check-In Details for {property_name}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Welcome! Here's everything you need for check-in:<br>Room Number: {room_number}<br>Arrival Date: {check_in}<br><br>See you soon!<br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 20,
                'apply_to' => 'booking',
                'name' => 'Mid-Stay Check-In',
                'subject' => 'How is Your Stay So Far at {property_name}?',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We hope you're enjoying your stay. If you need anything, we're here for you.<br><br>--{company_name} Team",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 4,
                'apply_to' => 'booking',
                'name' => 'Check-Out Reminder',
                'subject' => 'Check-Out Reminder for Your Stay at {property_name}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>This is a kind reminder that your check-out is scheduled for {check_out}.<br>We hope you had a great stay!<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 5,
                'apply_to' => 'booking',
                'name' => 'Cancellation Confirmation',
                'subject' => 'Your Booking {reference} has been Cancelled',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Your reservation {reference} has been cancelled. If you have questions about the refund, reach out to us.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 6,
                'apply_to' => 'booking',
                'name' => 'Reservation Update Confirmation',
                'subject' => 'Your Booking {reference} has been Updated',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Your booking has been updated with the latest details. Please check your dashboard for more info.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 7,
                'apply_to' => 'feedback',
                'name' => 'We\'d Love Your Feedback',
                'subject' => 'How Was Your Stay at {property_name}?',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We hope you enjoyed your stay! Could you take a minute to leave us a review?<br>Your feedback helps us grow.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 8,
                'apply_to' => 'promotion',
                'name' => 'Special Offer Just for You',
                'subject' => 'Exclusive Offer for Your Next Stay at {property_name}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We’d love to host you again. Here's a <b>{discount}% discount</b> for your next visit!<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 9,
                'apply_to' => 'birthday',
                'name' => 'Happy Birthday',
                'subject' => '🎉 Happy Birthday, {guest_name}!',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We at {company_name} wish you a wonderful birthday!<br>Here’s a small gift for your next stay.<br><br>🎁 Enjoy!",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 10,
                'apply_to' => 'payment',
                'name' => 'Payment Confirmation',
                'subject' => 'Payment Received for Booking {reference} - {property_name}',
                'subjectSearch' => ['{reference}', '{property_name}'],
                'content' => "Hi {guest_name},<br>We’ve received your payment of <b>{total_amount}</b> for booking {reference}.<br>Thank you!<br><br>--{company_name}",
                'contentSearch' => ['{guest_name}', '{total_amount}', '{reference}', '{company_name}'],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 11,
                'apply_to' => 'invoice',
                'name' => 'Booking Invoice',
                'subject' => 'Invoice for Booking {reference} at {property_name}',
                'subjectSearch' => ['{property_name}', '{reference}'],
                'content' => "Hi {guest_name},<br>Attached is your invoice for your recent stay. Amount: <b>{total_amount}</b>.<br><br>--{company_name}",
                'contentSearch' => ['{total_amount}', '{reference}', '{guest_name}', '{company_name}'],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 12,
                'apply_to' => 'invoice',
                'name' => 'Invoice Payment Reminder',
                'subject' => 'Reminder: Invoice Due for Booking {reference}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>This is a reminder to complete the payment of <b>{total_amount}</b> for your stay.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 13,
                'apply_to' => 'invoice',
                'name' => 'Overdue Payment Notice',
                'subject' => 'Action Required: Overdue Payment for Booking {reference}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Your payment for booking {reference} is overdue. Please clear it to avoid penalties.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 14,
                'apply_to' => 'refund',
                'name' => 'Refund Processed',
                'subject' => 'Refund Issued for Booking {reference}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We’ve processed your refund of <b>{refund_amount}</b> for booking {reference}.<br>Expect to see it within 3-5 business days.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 15,
                'apply_to' => 'maintenance',
                'name' => 'Maintenance Report Submitted',
                'subject' => 'Maintenance Request for Room {room_number}',
                'subjectSearch' => [],
                'content' => "Hi Team,<br>A new maintenance issue has been reported in Room {room_number}.<br>Please review and act accordingly.<br><br>--Ndako System",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 16,
                'apply_to' => 'maintenance',
                'name' => 'Maintenance Request Update',
                'subject' => 'Update on Your Maintenance Request (Ref {reference})',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>We wanted to update you on the status of your request. It is now marked as {status}.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 17,
                'apply_to' => 'housekeeping',
                'name' => 'Housekeeping Notification',
                'subject' => 'Scheduled Housekeeping for Room {room_number}',
                'subjectSearch' => [],
                'content' => "Hi Team,<br>Housekeeping is scheduled for Room {room_number} on {scheduled_date}.<br><br>--Ndako System",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 18,
                'apply_to' => 'lease',
                'name' => 'Lease Expiry Reminder',
                'subject' => 'Your Lease Expires on {lease_end_date}',
                'subjectSearch' => [],
                'content' => "Hi {guest_name},<br>Your lease at {property_name} will end on {lease_end_date}.<br>Please contact us if you'd like to renew.<br><br>--{company_name}",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
            [
                'id' => 19,
                'apply_to' => 'booking',
                'name' => 'New Booking Staff Notification',
                'subject' => 'New Booking Received: {reference}',
                'subjectSearch' => [],
                'content' => "Hi Team,<br>A new booking has been made for {property_name} from {check_in} to {check_out}.<br>Please prepare accordingly.<br><br>--Ndako System",
                'contentSearch' => [],
                'recipientEmails' => 'laudbouetoumoussa@gmail.com',
            ],
        ];
    }


    /**
     * Generate a PDF attachment based on the template's apply_to type.
     *
     * @param array $data Data for PDF rendering (e.g., total_amount, guest_name).
     * @return string|null Path to the generated PDF or null if no attachment.
     */
    public function generatePdfAttachment(array $data, $template, $contact): ?string
    {
        $applyTo = $template->apply_to;
        $templateMap = [
            'invoice' => 'app::pdf.templates.invoice',
            'payment' => 'app::pdf.templates.payment',
            'booking-confirmation' => 'app::pdf.templates.booking-confirmation',
        ];

        if (!isset($templateMap[$applyTo])) {
            Log::info('No PDF template for apply_to', ['apply_to' => $applyTo]);
            return null;
        }

        try {
            // Prepare data for PDF
            $pdfData = array_merge([
                'guest_name' => $contact->name ?? 'Arden BOUET',
                'company_name' => current_company()->name ?? 'Mamba Resorts',
                // 'date' => ,
            ], $data);
            Log::info('PDF data prepared', ['pdfData' => $pdfData]);

            // Generate PDF
            $pdf = Pdf::loadView($templateMap[$applyTo], $pdfData);
            $filename = "attachments/{$applyTo}_{$template->id}_" . time() . '.pdf';
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


    /**
     * Send the email with optional attachment.
     *
     * @param array $recipientEmails Data for PDF rendering (e.g., total_amount, guest_name).
     * @throws \Exception If email sending fails.
     */
    public function sendEmail(
        $recipientEmails,
        $template,
        $subject,
        $content,
        $attachment,
        $file = null
    )
    {

        try {

            // Prepare recipients
            $recipients = collect($recipientEmails)
                ->flatten()
                ->unique()
                ->filter()
                ->toArray();

            if (empty($recipients)) {
                throw new \Exception('No valid recipients provided.');
            }

            // Determine attachment
            $attachmentPath = $attachment;
            if ($file) {
                $attachmentPath = $file->store('public/attachments');
                $attachmentPath = storage_path('app/' . $attachmentPath);
            }

            // Log email details
            Log::info('Preparing to send email', [
                'template_id' => $template->id,
                'apply_to' => $template->apply_to,
                'recipients' => $recipients,
                'attachment' => $attachmentPath,
            ]);

            // Send email
            Mail::to($recipients)->send(new Template(
                subject: $subject,
                content: $content,
                company: current_company(),
                attachment: $attachmentPath
            ));

            // Clean up temporary attachment
            if ($attachmentPath && $attachment && Storage::exists('public/' . basename($attachmentPath))) {
                Storage::delete('public/' . basename($attachmentPath));
            }

            // Show success alert
            LivewireAlert::title('Email Sent!')
                ->text('Email sent to all recipients successfully.')
                ->success()
                ->position('top-end')
                ->timer(4000)
                ->toast()
                ->show();

            // $this->closeModal();

        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'template_id' => $template->id,
                'recipients' => $recipientEmails,
                'error' => $e->getMessage(),
            ]);

            LivewireAlert::title('Email Failed')
                ->text('We couldn’t send the email. Please try again later.')
                ->error()
                ->position('top-end')
                ->timer(4000)
                ->toast()
                ->show();
        }
    }
}
