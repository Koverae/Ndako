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
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Services\GuestCommunicationService;
use Illuminate\Support\Facades\Log;

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

    private GuestCommunicationService $guestCommunicationService;

    public function boot(GuestCommunicationService $guestCommunicationService){
        $this->guestCommunicationService = $guestCommunicationService;
    }

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
        $subjectReplace = [],
        $contentReplace = [],
        $data = []
    ) {

        // Ensure $model is an array
        $this->model = is_array($model) ? $model : [];
        Log::info('Normalized model', ['model' => $this->model]);

        $this->templates = $this->guestCommunicationService->getTemplates();

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
        $recipientField = $this->template->recipientEmails ?? '';
        $this->recipient_emails = array_filter(array_merge(
            explode(',', $recipientField),
            [$this->contact ? $this->contact->email : null]
        ));
        Log::info('Recipient emails set', ['recipient_emails' => $this->recipient_emails]);

        // Replace placeholders in subject and content
        $this->subject = str_replace($this->template->subjectSearch, $subjectReplace, $this->template->subject);
        $content = str_replace($this->template->contentSearch, $contentReplace, $this->template->content);
        $this->content = preg_replace('/\{(.*?)\}/', '<b>{$1}</b>', $content);

        $this->template_id = $this->template->id;

        // Generate PDF attachment if applicable
        $this->attachment = $this->guestCommunicationService->generatePdfAttachment($data, $this->template, $this->contact);
        Log::info('Mount completed', ['attachment' => $this->attachment]);
    }

    /**
     * Clear the uploaded file and generated attachment.
     */
    public function clearFile()
    {
        $this->file = null;
        $this->attachment = null;
        Log::info('File and attachment cleared');
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
     * Send the email with optional attachment.
     *
     * @throws \Exception If email sending fails.
     */
    public function sendEmail()
    {
        // Validate inputs
        $this->validate();

        // Send Email
        $this->guestCommunicationService->sendEmail(
            $this->recipient_emails,
            $this->template,
            $this->subject,
            $this->content,
            $this->attachment
        );

        $this->closeModal();

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
