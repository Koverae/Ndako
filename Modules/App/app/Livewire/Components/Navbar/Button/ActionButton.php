<?php

namespace Modules\App\Livewire\Components\Navbar\Button;

class ActionButton{

    public string $component = 'button.action.special-button';

    public string $key;

    public string $label;

    public string $action;

    public bool $separator = false;

    public $condition = null;

    public function __construct($key, $label, $action, $separator = false, $condition = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
        $this->separator = $separator;
        $this->condition = $condition;
    }

    public static function make($key, $label, $action, $separator = false, $condition = null)
    {
        return new static($key, $label, $action, $separator, $condition);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}