<?php

namespace Modules\App\Livewire\Components\Navbar\Button;

class ActionDropdown{

    public string $component = 'app::button.action.dropdown';

    public string $key;

    public string $label;

    public string $action;

    public bool $separator = false, $isConfirm = false;

    public $condition = null, $icon = null, $confirmText = null;

    public function __construct($key, $label, $action, $separator = false, $icon = null, $isConfirm = false, $confirmText = null, $condition = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
        $this->separator = $separator;
        $this->icon = $icon;
        $this->isConfirm = $isConfirm;
        $this->confirmText = $confirmText;
        $this->condition = $condition;
    }

    public static function make($key, $label, $action, $separator = false, $icon = null, $isConfirm = false, $confirmText = null, $condition = null)
    {
        return new static($key, $label, $action, $separator, $icon, $isConfirm, $confirmText, $condition);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
