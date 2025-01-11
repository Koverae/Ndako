<?php

namespace Modules\App\Livewire\Components\Form\Button;

class ActionBarButton{

    public string $component = 'app::form.button.action-bar.simple';

    public $key, $label, $action, $primary, $parent;

    public function __construct($key, $label, $action, $primary = null, $parent = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->action = $action;
        $this->primary = $primary;
        $this->parent = $parent;
    }

    public static function make($key, $label, $action, $primary = null, $parent = null)
    {
        return new static($key, $label, $action, $primary, $parent);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
