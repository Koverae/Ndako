<?php

namespace Modules\App\Livewire\Components\Navbar\Button;

class ActionButton{

    public string $component = 'app::button.action.special-button';

    public string $key, $label, $action, $icon;

    public bool $separator = false;

    public $condition = null;

    public function __construct($key, $label, $action, $separator = false, $icon = "", $condition = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
        $this->separator = $separator;
        $this->icon = $icon;
        $this->condition = $condition;
    }

    public static function make($key, $label, $action, $separator = false, $icon = "", $condition = null)
    {
        return new static($key, $label, $action, $separator, $icon, $condition);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}