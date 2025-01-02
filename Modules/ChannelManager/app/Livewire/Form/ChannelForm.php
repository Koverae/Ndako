<?php

namespace Modules\ChannelManager\Livewire\Form;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\SimpleAvatarForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Livewire\Components\Table\Column;

class ChannelForm extends LightWeightForm
{
    public $channel;
    public $name, $status;

    public function mount($channel = null){
        $this->default_img = 'default_logo';
        $this->blocked = true;
        if($channel){
            $this->channel = $channel;
            $this->name = $channel->name;
            $this->image_path = $channel->avatar;
        }
    }

    public function capsules() : array
    {
        return [
            Capsule::make('reservation', __('Reservations'), __('Reservations made via this channel'), 'link', 'fa fa-calendar-check'),
            Capsule::make('sync-logs', __('Sync Logs'), __('Log of connections and actions'), 'link', 'fa fa-tasks'),
            Capsule::make('analysis', __('Performance Analytics'), __('Channel performance analysis'), 'link', 'fa fa-signal'),
        ];
    }

    public function groups() : array
    {
        return [
            // Group::make('api',__("API Credentials"), ""),
        ];
    }


    public function inputs(): array
    {
        return [
            Input::make('name', "Channel Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Airbnb'))->component('app::form.input.ke-title'),
            Input::make('api-key', "API Key", 'text', 'airbnb_api_key', 'left', 'none', 'api', __('e.g. Airbnb')),
            Input::make('oauth-token', "OAuth Token", 'text', 'airbnb_oauth_token', 'left', 'none', 'api', __('e.g. Airbnb')),
            Input::make('name', "Channel Name", 'text', 'name', 'left', 'none', 'api', __('e.g. Airbnb')),
            Input::make('name', "Channel Name", 'text', 'name', 'left', 'none', 'api', __('e.g. Airbnb')),
        ];
    }
}
