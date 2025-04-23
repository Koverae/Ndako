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

    public bool $has_google_hotel_integration = false, $has_website_integration = false;
    public $google_hotel_client_id, $google_hotel_api_key, $google_hotel_bid;
    public $publicKey, $secretKey, $newDomain;
    public array $authorizedDomains = [];

    public function mount($setting){
        $this->setting = $setting;

        $this->publicKey = current_company()->client->public_key ;
        $this->secretKey = current_company()->client->private_key ;
        $this->authorizedDomains = current_company()->client->authorized_domains ?? [];

        $this->has_website_integration = $setting->has_website_integration ?? false;

        $this->google_hotel_client_id = $setting->google_hotel_client_id;
        $this->google_hotel_api_key = $setting->google_hotel_api_key;
        $this->google_hotel_bid = $setting->google_hotel_bid;

    }

    public function blocks(): array
    {
        return [
            Block::make('integration', __('Integration')),
        ];
    }

    public function boxes() : array
    {
        return [
            // Integrations
            Box::make('google-hotels', "Google Hotel Ads", 'has_google_hotel_integration', "Appear directly in Google search results, syncing your availability and pricing to capture potential guests instantly.", 'integration', true, "", null),
            Box::make('website-integration', "Website Integration", 'has_website_integration', "Skip the commissions. Let guests book and pay directly on your website with Ndako.", 'integration', true, "", null),
        ];
    }

    public function inputs() : array
    {
        return [
            // Google Hotels Ads
            BoxInput::make('client-id', "Client ID", 'text', 'google_hotel_client_id', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('api-key', "API Key", 'text', 'google_hotel_api_key', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
            BoxInput::make('bid', "Bid Management Settings", 'text', 'google_hotel_bid', 'google-hotels', '', false, [], $this->has_google_hotel_integration)->component('app::blocks.boxes.input.depends'),
            // Website Integration
            BoxInput::make('public-key', "API Public Key", 'text', 'publicKey', 'website-integration', '', false, [], $this->has_website_integration)->component('app::blocks.boxes.input.special.api'),
            BoxInput::make('secret-key', "API Secret Key", 'text', 'secretKey', 'website-integration', '', false, [], $this->has_website_integration)->component('app::blocks.boxes.input.special.api'),
            BoxInput::make('new-domain', "Authorized Domains", 'text', 'newDomain', 'website-integration', '', false, [], $this->has_website_integration)->component('app::blocks.boxes.input.special.authorized-domain'),
        ];
    }

    #[On('save')]
    public function save(){
        $setting = $this->setting;

        $setting->update([
            'has_website_integration' => $this->has_website_integration,
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
    public function addDomain()
    {
        $domain = trim($this->newDomain);

        if ($domain && !in_array($domain, $this->authorizedDomains)) {
            $this->authorizedDomains[] = $domain;
            $this->saveAuthorizedDomains(); // persist immediately
        }

        $this->newDomain = '';
    }

    public function removeDomain($domain)
    {
        $this->authorizedDomains = array_filter($this->authorizedDomains, fn ($item) => $item !== $domain);
        $this->saveAuthorizedDomains(); // persist immediately
    }

    public function saveAuthorizedDomains()
    {
        current_company()->client->update([
            'authorized_domains' => array_values($this->authorizedDomains),
        ]);
    }

}
