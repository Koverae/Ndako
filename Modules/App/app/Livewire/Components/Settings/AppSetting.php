<?php

namespace Modules\App\Livewire\Components\Settings;

use Livewire\Component;

abstract class AppSetting extends Component
{
    public $blocked = false;
    
    public function render()
    {
        return view('app::livewire.components.settings.app-setting');
    }

    public function blocks() : array{
        return [];
    }

    public function boxes() : array{
        return [];
    }
    
    public function inputs() : array{
        return [];
    }
    
    public function actions() : array{
        return [];
    }
}
