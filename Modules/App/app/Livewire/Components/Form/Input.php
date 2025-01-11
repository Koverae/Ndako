<?php

namespace Modules\App\Livewire\Components\Form;

class Input{

    public string $component = 'app::form.input.simple';
    public string $key;

    public $label, $type, $model, $position, $tab, $group, $placeholder, $help;
    public array $data = [];
    public bool $disabled = false;

    public function __construct($key, $label, $type, $model, $position, $tab, $group, $placeholder = null, $help = null, $data = [], $disabled = false)
    {
        $this->key = $key;
        $this->label = $label;
        $this->type = $type;
        $this->model = $model;
        $this->position = $position;
        $this->tab = $tab;
        $this->group = $group;
        $this->placeholder = $placeholder;
        $this->help = $help;
        $this->data = $data;
        $this->disabled = $disabled;
    }

    public static function make($key, $label, $type, $model, $position, $tab, $group, $placeholder = null, $help = null, $data = [], $disabled = false)
    {
        return new static($key, $label, $type, $model, $position, $tab, $group, $placeholder, $help, $data, $disabled);
    }


    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
