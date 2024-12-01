<?php

namespace Modules\App\Livewire\Components\Form\Button;

class ActionBarButton{

    public string $component = 'button.action-bar.simple';

    public string $key;

    public string $label;

    public string $action;

    public $primary;

    public function __construct($key, $label, $action, $primary = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
        $this->primary = $primary;
    }

    public static function make($key, $label, $action, $primary = null)
    {
        return new static($key, $label, $action, $primary);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
