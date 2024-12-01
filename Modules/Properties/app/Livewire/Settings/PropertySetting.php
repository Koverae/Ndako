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
use Modules\Properties\Models\Property\UnitStatus;

class PropertySetting extends AppSetting
{
    public $setting;
    public $has_default_unit_status, $has_default_numbering, $has_default_utility, $has_floor_mapping, $has_shared_amenties, $has_lease_term, $has_base_rental, $has_utility_rules, $has_pricelists, $has_discounts, $has_seasonal_discounts, $has_default_check_times, $has_online_payment, $has_lock_confirmed_booking, $has_pro_formatçinvoice, $has_overbooking_prevention, $has_stay_rule_per_unit, $has_cleaning_frequency, $has_maintenance_alerts, $has_housekeeping_staff, $has_maintenance_requests, $has_customer_portal, $has_in_room_services, $has_guest_note;
    public array $unitStatus = [];

    public function mount($setting)
    {
        $this->setting = $setting;
        $this->has_default_unit_status = $setting->has_default_unit_status;
        $this->has_default_numbering = $setting->has_default_numbering;
        $this->has_default_utility = $setting->has_default_utility;
        $this->has_floor_mapping = $setting->has_floor_mapping;
        $this->has_shared_amenties = $setting->has_shared_amenties;
        $this->has_lease_term = $setting->has_lease_term;
        $this->has_base_rental = $setting->has_base_rental;
        $this->has_utility_rules = $setting->has_utility_rules;
        $this->has_pricelists = $setting->has_pricelists;
        $this->has_discounts = $setting->has_discounts;
        $this->has_seasonal_discounts = $setting->has_seasonal_discounts;
        $this->has_default_check_times = $setting->has_default_check_times;
        $this->has_online_payment = $setting->has_online_payment;
        $this->has_lock_confirmed_booking = $setting->has_lock_confirmed_booking;
        $this->has_pro_formatçinvoice = $setting->has_pro_formatçinvoice;
        $this->has_overbooking_prevention = $setting->has_overbooking_prevention;
        $this->has_stay_rule_per_unit = $setting->has_stay_rule_per_unit;
        $this->has_cleaning_frequency = $setting->has_cleaning_frequency;
        $this->has_maintenance_alerts = $setting->has_maintenance_alerts;
        $this->has_housekeeping_staff = $setting->has_housekeeping_staff;
        $this->has_maintenance_requests = $setting->has_maintenance_requests;
        $this->has_customer_portal = $setting->has_customer_portal;
        $this->has_in_room_services = $setting->has_in_room_services;
        $this->has_guest_note = $setting->has_guest_note;
        
        $this->unitStatus = toSelectOptions(UnitStatus::all(), 'id', 'email');
    }

    public function blocks() : array
    {
        return [
            Block::make('general-settings', __('General Property Settings')),
            Block::make('accommodations', _('Accommodations & Features')),
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
            Box::make('default-unit-status', "Default Unit Status", 'default_unit_status', "Define initial status for rooms or units (e.g., Available, Maintenance, Rented).", 'general-settings', false, "", null),
            Box::make('default-unit-numbering', "Default Unit Numbering", 'default_numbering', "Define default numbering or naming (e.g., Apartment A101, Villa 3, 301).", 'general-settings', false, "", null),
            Box::make('default-utilities', "Default Utilities", 'default_utility', "Specify default utilities available per unit (e.g., water, electricity, internet, gas).", 'general-settings', false, "", null),
            Box::make('floor-mapping', "Floor/Building Mapping", 'has_floor_mapping', "Map units within floors or multiple buildings for easy navigation.", 'general-settings', true, "", null),
            // Accommodations & Features
            Box::make('shared-amenties', "Shared Amenities", 'has_shared_amenties', "Specify shared features like swimming pools, gyms, or parking spots.", 'accommodations', true, "", null),
            Box::make('lease-terms', "Lease Terms", 'has_lease_term', "Set standard lease durations for residential properties (e.g., monthly, yearly).", 'accommodations', true, "", null),
            Box::make('base-rental', "Base Rental/Room Rates", 'base_rental', "Define monthly rent or nightly rates for properties.", 'pricing', true, "", null),
            Box::make('utility-rules', "Utility Billing Rules", 'has_utility_rules', "Allow landlords to set utility billing options (e.g., included or separate).", 'pricing', true, "", null),
            Box::make('pricelists', "Pricelists", 'has_pricelists', "Set multiple prices per unit, automated discounts, etc.", 'pricing', true, "", null),
            Box::make('discounts', "Discounts, Loyalty and Gift Card", 'has_discounts', "Manage Promotions, Coupons, Loyalty cards, Gift cards & eWallet.", 'pricing', true, "", null),
            Box::make('seasonal-discounts', "Seasonal Discounts", 'has_seasonal_discounts', "Enable flexible pricing for peak and off-peak periods.", 'pricing', true, "", null),
            Box::make('default-check-time', "Default Check-in/Check-out Times", 'has_default_check_times', "Standardize guest arrival and departure hours.", 'booking-settings', true, "", null),
            Box::make('online-payment', "Online Payment", 'has_online_payment', "Request a payment to confirm booking, in full (100%) or partial. The default can be changed per order or template.", 'booking-settings', true, "https://ndako.koverae", null),
            Box::make('lock-confirm-booking', "Lock Confirmed Booking", 'has_lock_confirmed_booking', "No longer edit booking once confirmed", 'booking-settings', true, "", null),
            Box::make('pro-format', "Pro-Format Invoice", 'has_pro_format_invoice', "Allows you to send Pro-Forma Invoice to your guests / tenants", 'booking-settings', true, "https://ndako.koverae.com", null),
            Box::make('over-booking', "Overbooking Prevention", 'has_overbooking_prevention', "Automatically block double bookings for the same room/unit.", 'booking-settings', true, "", null),
            Box::make('stay-rules', "Minimum & Maximum Stay Rules", 'has_stay_rule_per_unit', "Limit the duration of bookings for specific rooms/units.", 'booking-settings', true, "", null),
            // Housekeeping & Maintenance
            Box::make('cleaning-frequency', "Cleaning Frequency", 'has_cleaning_frequency', "Set schedules for daily, weekly, or post-checkout cleaning.", 'housekeeping', true, "", null),
            Box::make('maintenance-alert', "Maintenance Alerts", 'has_maintenance_alerts', "Notify staff of required repairs or inspections.", 'housekeeping', true, "", null),
            Box::make('housekeeping-staff', "Housekeeping Staff Assignments", 'has_housekeeping_staff', "Allocate cleaning tasks to specific employees.", 'housekeeping', true, "", null),
            Box::make('maintenance-request', "Maintenance Requests", 'has_maintenance_requests', "Allow tenants to submit repair tickets directly.", 'housekeeping', true, "", null),
            // Resident and Guest Experience
            Box::make('customer-portal', "Tenant / Guest Portal", 'has_customer_portal', "Provide a platform for tenants to view lease details, pay rent, and report issues.", 'guest-experience', true, "", null),
            Box::make('in-room-services', "In-Room Services", 'has_in_room_services', "Enable ordering of room service or add-ons through a guest portal.", 'guest-experience', true, "", null),
            Box::make('guest-notes', "Guest Notes", 'has_guest_note', "Record specific guest preferences or past feedback for repeat stays.", 'guest-experience', true, "", null),
        ];
    }

    // Boxes Inputs
    public function inputs(): array
    {
        return [
            BoxInput::make('email-digest-templates', __('Templates'), 'select', 'digest_template', 'default-unit-status', '', false, $this->unitStatus),
        ];
    }
}
