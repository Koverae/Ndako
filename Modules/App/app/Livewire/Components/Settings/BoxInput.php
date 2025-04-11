<?php

namespace Modules\App\Livewire\Components\Settings;


class BoxInput
{
    public string $component = 'app::blocks.boxes.input.simple';
    public string $key;

    public $label;

    public string $type;

    public string $model;

    public string $box;

    public $placeholder;

    public $help;
    public array $data = [];
    public $parent;
    public bool $hasCopy = false;

    public function __construct($key, $label, $type, $model, $box, $placeholder = null, $help = null, $data = [], $parent = null, $hasCopy = false)
    {
        $this->key = $key;
        $this->label = $label;
        $this->type = $type;
        $this->model = $model;
        $this->box = $box;
        $this->placeholder = $placeholder;
        $this->help = $help;
        $this->data = $data;
        $this->parent = $parent;
        $this->hasCopy = $hasCopy;
    }

    public static function make($key, $label, $type, $model, $box, $placeholder = null, $help = null, $data = [], $parent = null, $hasCopy = false)
    {
        return new static($key, $label, $type, $model, $box, $placeholder, $help, $data, $parent, $hasCopy);
    }



    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
