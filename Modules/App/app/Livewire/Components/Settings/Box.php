<?php

namespace Modules\App\Livewire\Components\Settings;

class Box{

    public string $component = 'app::blocks.boxes.simple';

    public string $key;
    public string $label;
    public string $model;
    public $description;
    public string $block;
    public $help;
    public bool $checkbox;
    public $icon;

    public function __construct($key, $label, $model, $description, $block, $checkbox, $help = null, $icon = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->model = $model;
        $this->description = $description;
        $this->block = $block;
        $this->checkbox = $checkbox;
        $this->help = $help;
        $this->icon = $icon;
    }

    public static function make($key, $label, $model, $description, $block, $checkbox, $help = null, $icon = null)
    {
        return new static($key, $label, $model, $description, $block, $checkbox, $help, $icon);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
