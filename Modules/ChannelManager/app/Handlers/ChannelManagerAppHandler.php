<?php
namespace Modules\ChannelManager\Handlers;

use Modules\App\Handlers\AppHandler;
use Modules\ChannelManager\Models\Channel\Channel;

class ChannelManagerAppHandler extends AppHandler
{
    protected function getModuleSlug()
    {
        return 'channel-manager';
    }

    protected function handleInstallation($company)
    {
        // Example: Create settings-related data and initial configuration
        $this->configure($company);
    }

    protected function handleUninstallation()
    {
        // Example: Drop blog-related tables and clean up configurations
    }

    private function configure(int $companyId) : void
    {
        $channels = [
            [
                'company_id' => $companyId,
                'name' => 'Airbnb',
                'avatar' => 'airbnb.png',
                'api_endpoint' => 'https://api.airbnb.com/v2',
                'credentials' => json_encode([
                    'api_key' => 'Your Airbnb API Key',
                    'secret' => 'Your Airbnb Secret Key',
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Booking.com',
                'avatar' => 'booking.png',
                'api_endpoint' => 'https://distribution-xml.booking.com',
                'credentials' => json_encode([
                    'username' => 'Your Booking.com Username',
                    'password' => 'Your Booking.com Password',
                ]),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Google Hotel Ads',
                'avatar' => 'google-hotel.png',
                'api_endpoint' => 'https://hotelads.googleapis.com',
                'credentials' => json_encode([
                    'client_id' => 'Your Google Client ID',
                    'client_secret' => 'Your Google Client Secret',
                ]),
            ],
        ];
        foreach ($channels as $channel) {
            Channel::create($channel);
        }
    }
}