<?php

namespace Modules\ChannelManager\Livewire\Settings;

use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\ChannelManager\Models\Channel\Channel;

class ChannelManagerSetting extends AppSetting
{
    public $setting;
    public bool $has_room_mapping, $has_cut_off_times, $has_airbnb_integration, $has_bookingcom_integration, $has_google_hotel_integration;
    public array $channels = [], $rateSync = [];
    public $default_channel, $rate_sync, $airbnb_api_key, $airbnb_oauth_token, $airbnb_webhooks_url, $bookingcom_hotel_id, $bookingcom_api_key, $bookingcom_username, $booking_xml_connection, $google_hotel_client_id, $google_hotel_api_key, $google_hotel_bid;

    public function mount($setting){
        $this->setting = $setting;
        $this->default_channel = $setting->default_channel;
        $this->rate_sync = $setting->rate_sync;
        $this->has_room_mapping = $setting->has_room_mapping;
        $this->has_cut_off_times = $setting->has_cut_off_times;
        $this->has_airbnb_integration = $setting->has_airbnb_integration;
        $this->airbnb_api_key = $setting->airbnb_api_key;
        $this->airbnb_oauth_token = $setting->airbnb_oauth_token;
        $this->airbnb_webhooks_url = $setting->airbnb_webhooks_url;
        $this->has_bookingcom_integration = $setting->has_bookingcom_integration;
        $this->bookingcom_hotel_id = $setting->bookingcom_hotel_id;
        $this->bookingcom_api_key = $setting->bookingcom_api_key;
        $this->bookingcom_username = $setting->bookingcom_username;
        $this->booking_xml_connection = $setting->booking_xml_connection;
        $this->has_google_hotel_integration = $setting->has_google_hotel_integration;
        $this->google_hotel_client_id = $setting->google_hotel_client_id;
        $this->google_hotel_api_key = $setting->google_hotel_api_key;
        $this->google_hotel_bid = $setting->google_hotel_bid;
        
        $this->channels = toSelectOptions(Channel::isCompany(current_company()->id)->get(), 'id', 'name');
        
        $rateSync = [
            ['id' => 'automatically', 'label' => 'Automatically'],
            ['id' => 'manually', 'label' => 'Manually'],
        ];
        $this->rateSync = toSelectOptions($rateSync, 'id', 'label');
    }

    public function blocks(): array
    {
        return [
            Block::make('general-settings', __('General Settings')),
            Block::make('rate-availability', __('Rate & Availability Management')),
            Block::make('integration', __('Integration')),
        ];        
    }

    public function boxes() : array
    {
        return [
            Box::make('default-channel', "Default Channel Priority", ',', "Specify which platform overrides others in case of conflicting bookings.", 'general-settings', false, "", null),
            Box::make('room-mapping', "Room/Unit Mapping", 'has_room_mapping', "Map internal room/unit names to those on external platforms.", 'general-settings', true, "", null),
            // Rates Availability
            Box::make('rate-sync', "Rate Sync Rules", 'has_rate_sync', "Define whether rates are synced automatically or require manual approval.", 'rate-availability', false, "", null),
            Box::make('cut-off', "Booking Cut-off Times", 'has_cut_off_times', "Restrict last-minute bookings by channel.", 'rate-availability', true, "", null),
            // Integrations
            Box::make('airbnb', "Airbnb", 'has_airbnb_integration', "Connect with Airbnb to sync your listings, availability, and rates seamlessly while managing guest communication from one platform.", 'integration', true, "", null),
            Box::make('booking.com', "Booking.com", 'has_bookingcom_integration', "Manage bookings, availability, and pricing for one of the world’s largest travel platforms directly through Ndako.", 'integration', true, "", null),
            Box::make('google-hotels', "Google Hotel Ads", 'has_google_hotel_integration', "Appear directly in Google search results, syncing your availability and pricing to capture potential guests instantly.", 'integration', true, "", null),
        ];
    }

    public function inputs() : array
    {
        return [
            BoxInput::make('default-channel', "", 'select', 'default_channel', 'default-channel', '', false, $this->channels),
            BoxInput::make('rate-sync', "", 'select', 'rate_sync', 'rate-sync', '', false, $this->rateSync),
            // Airbnb
            BoxInput::make('api-key', "API Key", 'text', 'airbnb_api_key', 'airbnb', '', false, [], $this->has_airbnb_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('oauth-token', "OAuth Token", 'text', 'airbnb_api_key', 'airbnb', '', false, [], $this->has_airbnb_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('webhooks-url', "Webhooks URL", 'text', 'airbnb_webhooks_url', 'airbnb', '', false, [], $this->has_airbnb_integration)->component('app::blocks.boxes.input.depends'),
            // Booking.com
            BoxInput::make('hotel-id', "Hotel ID", 'text', 'bookingcom_hotel_id', 'booking.com', '', false, [], $this->has_bookingcom_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('api-key', "API Key", 'text', 'bookingcom_api_key', 'booking.com', '', false, [], $this->has_bookingcom_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('username-password', "Username/Password", 'text', 'bookingcom_username', 'booking.com', '', false, [], $this->has_bookingcom_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('xml-connection', "XML Connection", 'text', 'bookingcom_xml_connection', 'booking.com', '', false, [], $this->has_bookingcom_integration)->component('app::blocks.boxes.input.depends'),
            // Google Hotels Ads
            BoxInput::make('client-id', "Client ID", 'text', 'google_hotel_client_id', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('api-key', "API Key", 'text', 'google_hotel_api_key', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('bid', "Bid Management Settings", 'text', 'google_hotel_bid', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
        ];
    }

    #[On('save')]
    public function save(){
        $setting = $this->setting;

        $setting->update([
            'default_channel' => $this->default_channel,
            'rate_sync' => $this->rate_sync,
            'has_room_mapping' => $this->has_room_mapping,
            'has_cut_off_times' => $this->has_cut_off_times,
            'has_airbnb_integration' => $this->has_airbnb_integration,
            'airbnb_api_key' => $this->airbnb_api_key,
            'airbnb_oauth_token' => $this->airbnb_oauth_token,
            'airbnb_webhooks_url' => $this->airbnb_webhooks_url,
            'has_bookingcom_integration' => $this->has_bookingcom_integration,
            'bookingcom_hotel_id' => $this->bookingcom_hotel_id,
            'bookingcom_api_key' => $this->bookingcom_api_key,
            'bookingcom_username' => $this->bookingcom_username,
            'booking_xml_connection' => $this->booking_xml_connection,
            'has_google_hotel_integration' => $this->has_google_hotel_integration,
            'google_hotel_client_id' => $this->google_hotel_client_id,
            'google_hotel_api_key' => $this->google_hotel_api_key,
            'google_hotel_bid' => $this->google_hotel_bid,
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
