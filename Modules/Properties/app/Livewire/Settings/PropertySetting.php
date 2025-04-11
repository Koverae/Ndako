<?php

namespace Modules\Properties\Livewire\Settings;

use App\Models\User;
use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\Properties\Models\Property\Amenity;
use Modules\Properties\Models\Property\UnitStatus;
use Modules\Properties\Models\Property\Utility;

class PropertySetting extends AppSetting
{
    public $setting;
    public bool $has_default_unit_status = false, $has_default_numbering = false, $has_default_utility = false, $has_floor_mapping = false, $has_shared_amenities = false, $has_refund_policy = true,  $has_lease_term = false, $has_base_rental = false, $has_down_payment = true, $has_utility_rules = false, $has_pricelists = false, $has_discounts = false, $has_seasonal_discounts = false, $has_default_check_times = false, $has_online_payment = false, $has_lock_confirmed_booking = false, $has_pro_format_invoice = false, $has_overbooking_prevention = false, $has_stay_rule_per_unit = false, $has_cleaning_frequency = false, $has_maintenance_alerts = false, $has_housekeeping_staff = false, $has_maintenance_requests = false, $has_customer_portal = false, $has_in_room_services = false, $has_guest_note = false;
    public $base_rental, $utility_rule, $full_refund_days, $partial_refund_days, $partial_refund_percentage, $last_minute_refund_days, $default_check_in_time, $default_check_out_time;
    public $downPayment = 0;
    public array $unitStatus = [], $numberingSystems = [], $companyUtilities = [], $utilities = [], $sharedAmenities = [], $companyAmenities = [], $leaseTerms = [], $utilityRules = [];

    public function mount($setting)
    {
        $this->setting = $setting;
        $this->has_default_unit_status = $setting->has_default_unit_status;

        $this->downPayment = $setting->down_payment;
        $this->has_refund_policy = $setting->has_refund_policy;
        $this->full_refund_days = $setting->full_refund_days;
        $this->partial_refund_days = $setting->partial_refund_days;
        $this->partial_refund_percentage = $setting->partial_refund_percentage;
        $this->last_minute_refund_days = $setting->last_minute_refund_days;

        $this->has_lease_term = $setting->has_lease_term ?? false;

        $this->has_utility_rules = $setting->has_utility_rules;
        $this->utility_rule = $setting->utility_rule;
        $this->has_pricelists = $setting->has_pricelists;
        $this->has_discounts = $setting->has_discounts;
        $this->has_seasonal_discounts = $setting->has_seasonal_discounts;

        $this->has_default_check_times = $setting->has_default_check_times;
        $this->default_check_in_time = $setting->default_check_in_time;
        $this->default_check_out_time = $setting->default_check_out_time;
        $this->has_online_payment = $setting->has_online_payment;
        $this->has_lock_confirmed_booking = $setting->has_lock_confirmed_booking;
        $this->has_pro_format_invoice = $setting->has_pro_format_invoice;
        $this->has_overbooking_prevention = $setting->has_overbooking_prevention;
        $this->has_stay_rule_per_unit = $setting->has_stay_rule_per_unit;
        $this->has_cleaning_frequency = $setting->has_cleaning_frequency;
        $this->has_maintenance_alerts = $setting->has_maintenance_alerts;
        $this->has_housekeeping_staff = $setting->has_housekeeping_staff;
        $this->has_maintenance_requests = $setting->has_maintenance_requests;
        $this->has_customer_portal = $setting->has_customer_portal;
        $this->has_in_room_services = $setting->has_in_room_services;
        $this->has_guest_note = $setting->has_guest_note;

        $numberingSystems = [
            ['id' => 'sequential-numbering', 'label' => 'Sequential Numbering', 'desc' => 'Numbers are assigned sequentially, often starting at 101 for the first room on the first floor, 201 for the first room on the second floor, etc.'],
            ['id' => 'alphanumeric-numbering', 'label' => 'Alphanumeric Numbering', 'desc' => 'Combines letters for blocks or buildings (e.g., A, B, C) and numbers for units or floors (e.g., 101 for the first floor, unit 1).'],
            ['id' => 'zonal-numbering', 'label' => 'Zonal Numbering', 'desc' => 'Assigns zones or areas (e.g., Villa 3, Zone B), followed by unit numbers or floor designations (e.g., 301 for the 3rd floor).'],
            ['id' => 'floor-based-numbering', 'label' => 'Floor-Based Numbering', 'desc' => 'The first digit indicates the floor number (e.g., 3 = 3rd floor), followed by the room number (e.g., 01 = first room).'],
        ];
        $this->numberingSystems = toSelectOptions($numberingSystems, 'id', 'label');

        $this->utilities = toSelectOptions(Utility::all(), 'id', 'name');

        $utilityRules = [
            ['id' => 'included', 'label' => 'Included'],
            ['id' => 'separate', 'label' => 'Separate'],
        ];
        $this->utilityRules = toSelectOptions($utilityRules, 'id', 'label');

        $companyUtilities = [
            ['id' => 'water', 'label' => 'Water'],
            ['id' => 'electricity', 'label' => 'Electricity'],
            ['id' => 'internet', 'label' => 'Internet'],
            ['id' => 'cable-tv', 'label' => 'Cable TV'],
            ['id' => 'waste', 'label' => 'Waste Management'],
        ];
        $this->companyUtilities = toSelectOptions($companyUtilities, 'id', 'label');

        $this->sharedAmenities = toSelectOptions(Amenity::isCompany(current_company()->id)->get(), 'id', 'name');
        $companyAmenities = [
            ['id' => 'swimming-pool', 'label' => 'Swimming Pool'],
            ['id' => 'gym', 'label' => 'Gym'],
            ['id' => 'parking', 'label' => 'Parking'],
        ];
        $this->companyAmenities = toSelectOptions($companyAmenities, 'id', 'label');

        $leaseTerms = [
            ['id' => 'weekly', 'label' => 'Weekly'],
            ['id' => 'monthly', 'label' => 'Monthly'],
            ['id' => 'quarterly', 'label' => 'Quarterly'],
            ['id' => 'yearly', 'label' => 'Yearly'],
        ];
        $this->leaseTerms = toSelectOptions($leaseTerms, 'id', 'label');

        $this->unitStatus = toSelectOptions(UnitStatus::all(), 'id', 'name');
    }

    public function blocks() : array
    {
        return [
            // Block::make('general-settings', __('General Property Settings')),
            // Block::make('accommodations', _('Accommodations & Features')),
            Block::make('pricing', __('Pricing & Discounts')),
            Block::make('booking-settings', 'Booking Settings'),
            Block::make('housekeeping', 'Housekeeping & Maintenance'),
            Block::make('guest-experience', 'Resident and Guest Experience'),
            // Add more buttons as needed
        ];
    }

    public function boxes() :array
    {
        return [
            // Pricing
            Box::make('down-payment', "Down Payment (%)", 'has_down_payment', "Initial payment made to secure a booking.", 'pricing', false, "", null),
            // Box::make('utility-rules', "Utility Billing Rules", 'has_utility_rules', "Allow landlords to set utility billing options (e.g., included or separate).", 'pricing', true, "", null),
            Box::make('pricelists', "Pricelists", 'has_pricelists', "Set multiple prices per unit, automated discounts, etc.", 'pricing', true, "", null),
            // Box::make('discounts', "Discounts, Loyalty and Gift Card", 'has_discounts', "Manage Promotions, Coupons, Loyalty cards, Gift cards & eWallet.", 'pricing', true, "", null),
            // Box::make('seasonal-discounts', "Seasonal Discounts", 'has_seasonal_discounts', "Enable flexible pricing for peak and off-peak periods.", 'pricing', true, "", null),
            Box::make('default-check-time', "Default Check-in/Check-out Times", 'has_default_check_times', "Standardize guest arrival and departure hours.", 'booking-settings', true, "", null),
            // Box::make('online-payment', "Online Payment", 'has_online_payment', "Request a payment to confirm booking, in full (100%) or partial. The default can be changed per order or template.", 'booking-settings', true, "https://ndako.koverae", null),
            Box::make('lock-confirm-booking', "Lock Confirmed Booking", 'has_lock_confirmed_booking', "No longer edit booking once confirmed", 'booking-settings', true, "", null),
            Box::make('over-booking', "Overbooking Prevention", 'has_overbooking_prevention', "Automatically block double bookings for the same room/unit.", 'booking-settings', true, "", null),
            Box::make('stay-rules', "Minimum & Maximum Stay Rules", 'has_stay_rule_per_unit', "Limit the duration of bookings for specific rooms/units.", 'booking-settings', true, "", null),
            Box::make('refund-policy', "Refund Policy", 'has_refund_policy', "Define your cancellation terms and refund conditions with ease.", 'booking-settings', true, "https://ndako.koverae/docs", null),
            // Housekeeping & Maintenance
            // Box::make('cleaning-frequency', "Cleaning Frequency", 'has_cleaning_frequency', "Set schedules for daily, weekly, or post-checkout cleaning.", 'housekeeping', true, "", null),
            Box::make('maintenance-alert', "Maintenance Alerts", 'has_maintenance_alerts', "Notify staff of required repairs or inspections.", 'housekeeping', true, "", null),
            // Box::make('housekeeping-staff', "Housekeeping Staff Assignments", 'has_housekeeping_staff', "Allocate cleaning tasks to specific employees.", 'housekeeping', true, "", null),
            Box::make('maintenance-request', "Maintenance Requests", 'has_maintenance_requests', "Allow tenants to submit repair tickets directly.", 'housekeeping', true, "", null),
            // Resident and Guest Experience
            Box::make('customer-portal', "Guest Portal", 'has_customer_portal', "Provide a platform for guests to view booking details, pay, and report issues.", 'guest-experience', true, "", null),
            // Box::make('in-room-services', "In-Room Services", 'has_in_room_services', "Enable ordering of room service or add-ons through a guest portal.", 'guest-experience', true, "", null),
            Box::make('guest-notes', "Guest Notes", 'has_guest_note', "Record specific guest preferences or past feedback for repeat stays.", 'guest-experience', true, "", null),
        ];
    }

    // Boxes Inputs
    public function inputs(): array
    {
        return [
            BoxInput::make('default-status', "", 'select', 'defult_status', 'default-unit-status', '', false, $this->unitStatus),
            BoxInput::make('numbering-systems', "", 'select', 'default_numbering', 'default-unit-numbering', '', false, $this->numberingSystems),
            BoxInput::make('utilities', "", 'tag', 'default_utility', 'default-utilities', '', false, ['options' => $this->utilities, 'data' => $this->companyUtilities]),
            BoxInput::make('amenities', "", 'tag', 'default_utility', 'shared-amenities', '', false, ['options' => $this->sharedAmenities, 'data' => $this->companyAmenities], $this->has_shared_amenities)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('lease-terms', "", 'select', 'default_lease_term', 'lease-terms', '', false, $this->leaseTerms, $this->has_lease_term)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('down-payment-price', "", 'tel', 'downPayment', 'down-payment', '', false, []),
            BoxInput::make('utility-billing', "", 'select', 'utility_rule', 'utility-rules', '', false, $this->utilityRules, $this->has_utility_rules)->component('app::blocks.boxes.input.depends'),
            // Default Check Time
            BoxInput::make('check-in', "Check In", 'time', 'default_check_in_time', 'default-check-time', '', false, [], $this->has_default_check_times)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('check-out', "Check Out", 'time', 'default_check_out_time', 'default-check-time', '', false, [], $this->has_default_check_times)->component('app::blocks.boxes.input.depends'),
            // Website Integration
            BoxInput::make('full-refund', "Full Refund Days:", 'text', 'full_refund_days', 'refund-policy', '', false, [], $this->has_refund_policy)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('partial-refund', "Partial Refund Days:", 'text', 'partial_refund_days', 'refund-policy', '', false, [], $this->has_refund_policy)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('partial-refund-percentage', "Partial Refund (%):", 'text', 'partial_refund_percentage', 'refund-policy', '', false, [], $this->has_refund_policy)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('no-refund', "No Refund If Canceled Within:", 'text', 'last_minute_refund_days', 'refund-policy', '', false, [], $this->has_refund_policy)->component('app::blocks.boxes.input.depends'),
        ];
    }

    // Boxes Actions
    public function actions(): array
    {
        return [
            BoxAction::make('pricelists', 'pricelists', __('Pricelists'), 'link', 'bi-arrow-right', "", [], $this->has_pricelists)->component('app::blocks.boxes.action.depends'),
        ];
    }

    #[On('save')]
    public function save(){
        // $this->validate();

        $setting = $this->setting;
        $setting->update([
            'down_payment' => $this->downPayment,
            'has_utility_rules' => $this->has_utility_rules,
            'utility_rule' => $this->utility_rule,
            'has_pricelists' => $this->has_pricelists,
            'has_discounts' => $this->has_discounts,
            'has_seasonal_discounts' => $this->has_seasonal_discounts,
            'has_default_check_times' => $this->has_default_check_times,
            'default_check_in_time' => $this->default_check_in_time,
            'default_check_out_time' => $this->default_check_out_time,
            'has_online_payment' => $this->has_online_payment,
            'has_lock_confirmed_booking' => $this->has_lock_confirmed_booking,
            'has_overbooking_prevention' => $this->has_overbooking_prevention,
            'has_stay_rule_per_unit' => $this->has_stay_rule_per_unit,
            'has_cleaning_frequency' => $this->has_cleaning_frequency,
            'has_maintenance_alerts' => $this->has_maintenance_alerts,
            'has_housekeeping_staff' => $this->has_housekeeping_staff,
            'has_maintenance_requests' => $this->has_maintenance_requests,
            'has_customer_portal' => $this->has_customer_portal,
            'has_in_room_services' => $this->has_in_room_services,
            'has_guest_note' => $this->has_guest_note,
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
