<?php

namespace Modules\App\Livewire\Modal;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Modules\App\Models\Email\EmailTemplate;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\App\Emails\Template;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * A reusable Livewire modal component for sending templated emails with optional PDF attachments.
 * Supports dynamic placeholder replacement and case-specific PDF generation (e.g., invoices, payments).
 */
class SendByEmailModal extends ModalComponent
{
    use WithFileUploads;

    /** @var mixed Model data passed to the modal (e.g., booking, invoice). */
    public $model;

    /** @var object Selected email template. */
    public $template;

    /** @var string Email input for adding recipients. */
    public $email;

    /** @var string Email subject with replaced placeholders. */
    public $subject;

    /** @var string Email content with replaced placeholders. */
    public $content;

    /** @var int Selected template ID. */
    public $template_id;

    /** @var Guest|null Guest/contact associated with the email. */
    public $contact;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null Optional uploaded file. */
    public $file;

    /** @var string|null Path to generated PDF attachment (if applicable). */
    public $attachment;

    /** @var array List of available email templates. */
    public array $templates = [];

    /** @var array List of recipient email addresses. */
    public array $recipient_emails = [];

    /**
     * Initialize the modal with template and model data, replacing placeholders.
     *
     * @param int $templateId ID of the selected email template.
     * @param mixed $model Model data (e.g., booking, invoice).
     * @param array $subjectSearch Placeholders to search in subject.
     * @param array $subjectReplace Values to replace in subject.
     * @param array $contentSearch Placeholders to search in content.
     * @param array $contentReplace Values to replace in content.
     * @param array $data Additional data for PDF generation (e.g., total_amount, guest_name).
     */
    public function mount(
        $templateId,
        $model,
        $subjectSearch = [],
        $subjectReplace = [],
        $contentSearch = [],
        $contentReplace = [],
        $data = []
    ) {
        // Log all mount parameters
        Log::info('SendByEmailModal mount called', [
            'templateId' => $templateId,
            'model' => $model,
            'model_type' => gettype($model),
            'subjectSearch' => $subjectSearch,
            'subjectReplace' => $subjectReplace,
            'contentSearch' => $contentSearch,
            'contentReplace' => $contentReplace,
            'data' => $data,
        ]);

        // Ensure $model is an array
        $this->model = is_array($model) ? $model : [];
        Log::info('Normalized model', ['model' => $this->model]);

        $this->templates = $this->getTemplates();

        // Find selected template
        $selected = collect($this->templates)->firstWhere('id', $templateId);
        if (!$selected) {
            Log::warning('Invalid template ID provided', ['template_id' => $templateId]);
            LivewireAlert::title('Error')
                ->text('Invalid email template selected.')
                ->error()
                ->position('top-end')
                ->timer(4000)
                ->toast()
                ->show();
            $this->closeModal();
            return;
        }
        $this->template = (object) $selected;
        Log::info('Template selected', ['template' => $selected]);

        // Load guest/contact info
        $guestId = null;
        if (isset($this->model['guest_id']) && is_scalar($this->model['guest_id'])) {
            $guestId = (int) $this->model['guest_id'];
        } else {
            Log::warning('Invalid or missing guest_id', ['model' => $this->model]);
        }

        $this->contact = $guestId ? Guest::find($guestId) : null;
        if (!$this->contact && $guestId) {
            Log::warning('Guest not found', ['guest_id' => $guestId]);
        }

        // Set recipient emails
        $recipientField = $this->template->recipient_emails ?? '';
        $this->recipient_emails = array_filter(array_merge(
            explode(',', $recipientField),
            [$this->contact ? $this->contact->email : null]
        ));
        Log::info('Recipient emails set', ['recipient_emails' => $this->recipient_emails]);

        // Replace placeholders in subject and content
        $this->subject = str_replace($subjectSearch, $subjectReplace, $this->template->subject);
        $content = str_replace($contentSearch, $contentReplace, $this->template->content);
        $this->content = preg_replace('/\{(.*?)\}/', '<b>{$1}</b>', $content);

        $this->template_id = $this->template->id;

        // Generate PDF attachment if applicable
        $this->attachment = $this->generatePdfAttachment($data);
        Log::info('Mount completed', ['attachment' => $this->attachment]);
    }

    /**
     * Add a new email to the recipient list after validation.
     */
    public function addEmail()
    {
        $this->validate([
            'email' => ['required', 'email', Rule::notIn($this->recipient_emails)],
        ]);

        $this->recipient_emails[] = $this->email;
        $this->email = '';
    }

    /**
     * Remove an email from the recipient list.
     *
     * @param int $index Index of the email to remove.
     */
    public function removeEmail($index)
    {
        unset($this->recipient_emails[$index]);
        $this->recipient_emails = array_values($this->recipient_emails);
    }

    /**
     * Generate a PDF attachment based on the template's apply_to type.
     *
     * @param array $data Data for PDF rendering (e.g., total_amount, guest_name).
     * @return string|null Path to the generated PDF or null if no attachment.
     */
    protected function generatePdfAttachment(array $data): ?string
    {
        $applyTo = $this->template->apply_to;
        $templateMap = [
            'invoice' => 'templates.invoice',
            'payment' => 'templates.payment',
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
                'date' => now()->format('d M Y'),
            ], $data);
            Log::info('PDF data prepared', ['pdfData' => $pdfData]);

            // Generate PDF
            $pdf = Pdf::loadView($templateMap[$applyTo], $pdfData);
            $filename = "attachments/{$applyTo}_{$this->template_id}_" . time() . '.pdf';
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
     * Load available email templates.
     *
     * @return array List of template arrays.
     */
    protected function getTemplates(): array
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
     * Send the email with optional attachment.
     *
     * @throws \Exception If email sending fails.
     */
    public function sendEmail()
    {
        try {
            // Validate inputs
            $this->validate([
                'recipient_emails' => ['required', 'array', 'min:1'],
                'recipient_emails.*' => ['email'],
                'subject' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            ]);

            // Prepare recipients
            $recipients = collect($this->recipient_emails)
                ->flatten()
                ->unique()
                ->filter()
                ->toArray();

            if (empty($recipients)) {
                throw new \Exception('No valid recipients provided.');
            }

            // Determine attachment
            $attachmentPath = $this->attachment;
            if ($this->file) {
                $attachmentPath = $this->file->store('public/attachments');
                $attachmentPath = storage_path('app/' . $attachmentPath);
            }

            // Log email details
            Log::info('Preparing to send email', [
                'template_id' => $this->template_id,
                'apply_to' => $this->template->apply_to,
                'recipients' => $recipients,
                'attachment' => $attachmentPath,
            ]);

            // Send email
            Mail::to($recipients)->send(new Template(
                subject: $this->subject,
                content: $this->content,
                company: current_company(),
                attachment: $attachmentPath
            ));

            // Clean up temporary attachment
            if ($attachmentPath && $this->attachment && Storage::exists('public/' . basename($attachmentPath))) {
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

            $this->closeModal();

        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'template_id' => $this->template_id,
                'recipients' => $this->recipient_emails,
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

    /**
     * Render the modal view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('app::livewire.modal.send-by-email-modal');
    }

    /**
     * Define validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'email' => ['nullable', 'email', Rule::notIn($this->recipient_emails)],
            'recipient_emails' => ['required', 'array', 'min:1'],
            'recipient_emails.*' => ['email'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }
}
