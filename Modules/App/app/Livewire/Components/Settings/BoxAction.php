<?php

namespace Modules\App\Livewire\Components\Settings;

class BoxAction
{
    public string $component = 'app::blocks.boxes.action.simple';
    public string $key;

    public string $box;

    public string $label;

    public string $type;

    public $icon;
    public $action;
    public $data = [];
    public $parent;

    public function __construct($key, $box, $label, $type, $icon = null, $action = null, $data = [], $parent = null)
    {
        $this->key = $key;
        $this->box = $box;
        $this->label = $label;
        $this->type = $type;
        $this->icon = $icon;
        $this->action = $action;
        $this->data = $data;
        $this->parent = $parent;
    }

    public static function make($key, $box, $label, $type, $icon = null, $action = null, $data = [], $parent = null)
    {
        return new static($key, $box, $label, $type, $icon, $action, $data, $parent);
    }

    public function component($component, $data = [])
    {
        $this->component = $component;
        // $this->data = $data;

        return $this;
    }
}