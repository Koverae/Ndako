<?php

namespace Modules\App\Livewire\Components\Form\Button;

class ActionButton{

    public string $component = 'button.action.special-button';

    public string $key;

    public string $label;

    public string $action;

    public function __construct($key, $label, $action)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
    }

    public static function make($key, $label, $action)
    {
        return new static($key, $label, $action);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
