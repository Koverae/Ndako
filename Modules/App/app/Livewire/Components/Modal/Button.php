<?php

namespace Modules\App\Livewire\Components\Modal;

class Button{

    public string $component = 'modal.button.simple';

    public string $key;

    public string $label;
    public string $class;
    public string $type;
    public $action;

    public function __construct($key, $label, $class, $type, $action = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->class = $class;
        $this->type = $type;
        $this->action = $action;
    }

    public static function make($key, $label, $class, $type, $action = null)
    {
        return new static($key, $label, $class, $type, $action);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

}