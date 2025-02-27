<?php

namespace Modules\Settings\Livewire\Settings;


use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\CompanyInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\Settings\Models\Currency\Currency;
use Modules\Settings\Notifications\CompanyInvitationNotification;

class General extends AppSetting
{
    public $setting;

    public $friend_email, $pending_invitations, $users;

    // Customer Portal
    public array $customer_portal_access = [], $currenciesOptions = [], $cutomerPortalAccessOptions = [], $digest_templates = [], $geolocationProvider = [];

    public $default_currency, $koverae_digest, $has_customer_account = 'on_invitation', $geolocation_provider = 'open_street_map';
    public $has_default_check_times, $has_online_payment, $has_lock_confirmed_booking, $has_pro_format_invoice, $has_overbooking_prevention, $has_stay_rule_per_unit, $has_cleaning_frequency, $has_maintenance_alerts, $has_housekeeping_staff, $has_maintenance_requests;
    public bool $has_digest_email = true, $has_default_access_right = true, $has_geo_localization = false, $has_recaptcha = false, $has_reset_password = true, $has_quick_find = true, $has_import_from_xls = true;

    public function mount($setting){
        $this->setting = $setting;
        $this->has_customer_account = $setting->has_customer_account;
        $this->default_currency = $setting->default_currency_id;
        $this->has_default_access_right = $setting->has_default_access_right;
        $this->has_geo_localization = $setting->has_geo_localization;
        $this->geolocation_provider = $setting->geolocation_provider;
        $this->has_recaptcha = $setting->has_recaptcha;
        $this->has_reset_password = $setting->has_reset_password;
        $this->has_import_from_xls = $setting->has_import_from_xls;

        $this->has_default_check_times = $setting->has_default_check_times;
        $this->has_online_payment = $setting->has_online_payment;
        $this->has_lock_confirmed_booking = $setting->has_lock_confirmed_booking;
        $this->has_pro_format_invoice = $setting->has_pro_formatçinvoice;
        $this->has_overbooking_prevention = $setting->has_overbooking_prevention;
        $this->has_stay_rule_per_unit = $setting->has_stay_rule_per_unit;
        $this->has_cleaning_frequency = $setting->has_cleaning_frequency;
        $this->has_maintenance_alerts = $setting->has_maintenance_alerts;
        $this->has_housekeeping_staff = $setting->has_housekeeping_staff;
        $this->has_maintenance_requests = $setting->has_maintenance_requests;

        $this->pending_invitations = CompanyInvitation::isCompany(current_company()->id)->get();
        $this->users = current_company()->users()->get();

        $this->digest_templates = toSelectOptions(User::all(), 'id', 'email');

        $this->currenciesOptions = toSelectOptions(Currency::isCompany(current_company()->id)->get(), 'id', 'currency_name');

        $geoProvider = [
            ['id' => 'open_street_map', 'label' => 'Open Street Map', 'key' => ''],
            ['id' => 'google_place_map', 'label' => 'Google Place Map', 'key' => '']
        ];
        $this->geolocationProvider = toSelectOptions($geoProvider, 'id', 'label');
        // Weight
        $this->customer_portal_access = [
            ['id' => 'kilograms', 'label' => 'Kg'],
            ['id' => 'pounds', 'label' => 'Ib']
        ];
        $this->cutomerPortalAccessOptions = toRadioOptions($this->customer_portal_access, 'id', 'label', 'on_invitation');

        $this->customer_portal_access = [
            ['id' => 'on_invitation', 'label' => 'Invitation'],
            ['id' => 'free_signup', 'label' => 'Free']
        ];
        $this->cutomerPortalAccessOptions = toRadioOptions($this->customer_portal_access, 'id', 'label', 'on_invitation');
    }

    public function blocks() : array
    {
        return [
            Block::make('users', __('Users')),
            Block::make('companies', __('Enterprises')),
            Block::make('permissions', 'Permissions'),
            Block::make('integrations', 'Integrations'),
            Block::make('booking-settings', 'Booking Settings'),
            Block::make('housekeeping', 'Housekeeping & Maintenance'),
            Block::make('devs', 'Developers'),
            Block::make('about', 'Ndako'),
            // Add more buttons as needed
        ];
    }

    public function boxes() : array{
        return [
            // Users
            Box::make('invite_users', __('Invite Users'), 'invitation', ' ', 'users', false)->component('app::blocks.boxes.user.invite-user'),
            Box::make('active_users', $this->users->count() .' Active Users', 'invitation', null, 'users', false, "https://www.ndako.koverae.com/docs", " bi-people-fill"),
            // Enterprise
            Box::make('current-company', current_company()->name, 'companny', current_company()->country, 'companies', false, null, " bi-building"),
            Box::make('document-layout', __('Document layout'), 'companny', __("Choose the layout of your documents"), 'companies', false, null, " bi-files"),
            Box::make('email-template', __('E-mail templates'), 'companny', __("Customize the look and feel of automated emails"), 'companies', false, null, " bi-envelope"),
            Box::make('main-currency', "Main Currency", 'default_currency', "Main currency of your company", 'companies', false, "", null),
            Box::make('languages', __('1 Language(s)'), 'invitation', null, 'companies', false, "https://www.ndako.koverae.com/docs", " bi-translate"),
            // Permissions
            Box::make('customer-portal', __('Guests Portal'), 'has_customer_account', __('Let your guests log in to access their booking details and invoices.'), 'permissions', false),
            Box::make('default-access', __('Default Access Rights'), 'has_default_access_right', __('Define custom access rights for new team members.'), 'permissions', true),
            Box::make('password-reset', __('Password Reset'), 'has_reset_password', __('Enable password reset from Login page'), 'permissions', true),
            Box::make('import-export', __('Import / Export'), 'has_import_from_xls', __('Allow users to import data from CSV/XLS/XLSX files'), 'permissions', true, "https://www.ndako.koverae.com/docs"),
            // Integrations
            Box::make('recaptcha', __('reCAPTCHA'), 'has_recaptcha', __('Protect your forms from spam and abuse.'), 'integrations', true, "https://www.ndako.koverae.com/docs"),
            Box::make('geolocation', __('Geolocation'), 'has_geo_localization', __('Geolocate your partners and customers.'), 'integrations', true, "https://www.ndako.koverae.com/docs"),
            // Booking Settings
            Box::make('default-check-time', "Default Check-in/Check-out Times", 'has_default_check_times', "Standardize guest arrival and departure hours.", 'booking-settings', true, "", null),
            Box::make('online-payment', "Online Payment", 'has_online_payment', "Request a payment to confirm booking, in full (100%) or partial. The default can be changed per order or template.", 'booking-settings', true, "https://ndako.koverae", null),
            Box::make('lock-confirm-booking', "Lock Confirmed Booking", 'has_lock_confirmed_booking', "No longer edit booking once confirmed", 'booking-settings', true, "", null),
            Box::make('pro-format', "Pro-Format Invoice", 'has_pro_format_invoice', "Allows you to send Pro-Forma Invoice to your guests", 'booking-settings', true, "https://ndako.koverae.com", null),
            Box::make('over-booking', "Overbooking Prevention", 'has_overbooking_prevention', "Automatically block double bookings for the same room/unit.", 'booking-settings', true, "", null),
            Box::make('stay-rules', "Minimum & Maximum Stay Rules", 'has_stay_rule_per_unit', "Limit the duration of bookings for specific rooms/units.", 'booking-settings', true, "", null),
            // Housekeeping & Maintenance
            Box::make('cleaning-frequency', "Cleaning Frequency", 'has_cleaning_frequency', "Set schedules for daily, weekly, or post-checkout cleaning.", 'housekeeping', true, "", null),
            Box::make('maintenance-alert', "Maintenance Alerts", 'has_maintenance_alerts', "Notify staff of required repairs or inspections.", 'housekeeping', true, "", null),
            Box::make('housekeeping-staff', "Housekeeping Staff Assignments", 'has_housekeeping_staff', "Allocate cleaning tasks to specific employees.", 'housekeeping', true, "", null),
            Box::make('maintenance-request', "Maintenance Requests", 'has_maintenance_requests', "Allow tenants to submit repair tickets directly.", 'housekeeping', true, "", null),
            // Developer
            Box::make('developers', __('Developers'), 'developers', null, 'devs', false, "https://www.ndako.koverae.com/docs")->component('app::blocks.boxes.template.developer'),
            // About
            Box::make('developers', __('Developers'), 'developers', null, 'about', false, "https://www.ndako.koverae.com/docs")->component('app::blocks.boxes.template.about'),
        ];
    }

    // Boxes Inputs
    public function inputs(): array
    {
        return [
            BoxInput::make('email-digest-templates', __('Templates'), 'select', 'digest_template', 'email-digest', '', false, $this->digest_templates),
            BoxInput::make('customer-portal-access', null, 'radio', 'has_customer_account', 'customer-portal', '', false, $this->cutomerPortalAccessOptions)->component('app::blocks.boxes.input.radio'),
            BoxInput::make('geolocation-provider', null, 'select', 'geolocation_provider', 'geolocation', '', false, $this->geolocationProvider, $this->geolocation_provider),
            BoxInput::make('main-currency', null, 'select', 'default_currency', 'main-currency', '', false, $this->currenciesOptions),
        ];
    }

    // Boxes Actions
    public function actions(): array
    {
        return [
            BoxAction::make('manage-users', 'active_users', __('Manage Users'), 'link', 'bi-arrow-right', route('settings.users')),
            BoxAction::make('email-digest-templates', 'email-digest', __('Configure'), 'link', 'bi-arrow-right'),
            BoxAction::make('add-language', 'languages', __('Add a language'), 'modal', 'bi-plus-circle-fill', "{component: 'settings::modal.add-language-modal'}"),
            // BoxAction::make('manage-languages', 'languages', __('Manage languages'), 'link', 'bi-arrow-right'),
            BoxAction::make('update-company', 'current-company', __('Update Information'), 'link', 'bi-arrow-right'),
            BoxAction::make('configure-layout', 'document-layout', __('Configure'), 'link', 'bi-arrow-right'),
            BoxAction::make('email-template', 'email-template', __('Configure'), 'link', 'bi-arrow-right'),
            BoxAction::make('default-access', 'customer-portal', __('Default access rights'), 'link', 'bi-arrow-right'),
            BoxAction::make('default-access', 'default-access', __('Default access rights'), 'link', 'bi-arrow-right'),
            BoxAction::make('buy-credit-quick', 'quick-find', __('Buy Kredit'), 'link', 'bi-arrow-right'),
            BoxAction::make('buy-credit-sms', 'send-sms', __('Buy Kredit'), 'link', 'bi-arrow-right'),
            BoxAction::make('koverae-iap-view', 'koverae-iap', __('View My Kover Services'), 'link', 'bi-arrow-right'),
        ];
    }


    public function sendInvitation(){
        // Validate the form data
        $this->validate([
            'friend_email' => 'required|email|unique:company_invitations,email',
        ]);

        // Generate a unique invitation token
        $token = Str::random(32);

        // Create a new invitation record
        $invitation = CompanyInvitation::create([
            'team_id' => Auth::user()->team->id,
            'company_id' => current_company()->id,
            'email'     => $this->friend_email,
            'token' => $token,
            'role' => 'default',
            'expire_at' => now()->addDays(7),
            ]);
            $invitation->save();

            $invitation->notify(new CompanyInvitationNotification());
        // Send the invitation notification

        $this->friend_email = '';
        $this->pending_invitations = CompanyInvitation::isCompany(current_company()->id)->get();

    }

    public function deleteInvitation(CompanyInvitation $invitation){

        $invitation->delete();
        $this->pending_invitations = CompanyInvitation::isCompany(current_company()->id)->get();
    }

    #[On('save')]
    public function save(){
        $setting = $this->setting;

        $setting->update([
            'has_customer_account' => $this->has_customer_account,
            'has_digest_email' => $this->has_digest_email,
            'has_default_access_right' => $this->has_default_access_right,
            'has_geo_localization' => $this->has_geo_localization,
            'has_recaptcha' => $this->has_recaptcha,
            'has_reset_password' => $this->has_reset_password,
            'has_import_from_xls' => $this->has_import_from_xls,
            'has_quick_find' => $this->has_quick_find,
            'has_default_check_times' => $this->has_default_check_times,
            'has_online_payment' => $this->has_online_payment,
            'has_lock_confirmed_booking' => $this->has_lock_confirmed_booking,
            // 'has_pro_format_invoice' => $this->has_pro_format_invoice,
            'has_overbooking_prevention' => $this->has_overbooking_prevention,
            'has_stay_rule_per_unit' => $this->has_stay_rule_per_unit,
            'has_cleaning_frequency' => $this->has_cleaning_frequency,
            'has_maintenance_alerts' => $this->has_maintenance_alerts,
            'has_housekeeping_staff' => $this->has_housekeeping_staff,
            'has_maintenance_requests' => $this->has_maintenance_requests,
        ]);
        $setting->save();

        cache()->forget('settings');

        // notify()->success('Updates saved!');
        $this->dispatch('undo-change');

    }
    public function updated(){
        $this->dispatch('change');
    }


}
