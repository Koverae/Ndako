<?php

namespace Modules\App\Livewire\Components\Navbar;

class SwitchButton{

    public string $component = 'app::navbar.switch-button';
    public string $key;
    public string $action;

    public string $icon;


    public function __construct($key, $action, $icon)
    {
        $this->key = $key;
        $this->action = $action;
        $this->icon = $icon;
    }

    public static function make($key, $action, $icon)
    {
        return new static($key, $action, $icon);
    }


    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

}
