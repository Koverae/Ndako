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
    public $comment;

    public function __construct($key, $label, $model, $description, $block, $checkbox, $help = null, $icon = null, $comment = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->model = $model;
        $this->description = $description;
        $this->block = $block;
        $this->checkbox = $checkbox;
        $this->help = $help;
        $this->icon = $icon;
        $this->comment = $comment;
    }

    public static function make($key, $label, $model, $description, $block, $checkbox, $help = null, $icon = null, $comment = null)
    {
        return new static($key, $label, $model, $description, $block, $checkbox, $help, $icon, $comment);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
