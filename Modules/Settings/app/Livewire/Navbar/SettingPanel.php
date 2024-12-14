<?php

namespace Modules\Settings\Livewire\Navbar;

use Modules\App\Livewire\Components\Navbar\Template\Simple;
use Livewire\Component;

class SettingPanel extends Simple
{

    public function mount()
    {
        $this->currentPage = "Settings";
    }
}
