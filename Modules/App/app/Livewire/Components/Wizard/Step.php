<?php

namespace Modules\App\Livewire\Components\Wizard;

class Step{

    public string $component = 'app::wizard.step.simple';

    public string $key, $label;
    public bool $isActive = false;

    public function __construct($key, $label, $isActive)
    {
        $this->key = $key;
        $this->label = $label;
        $this->isActive = $isActive;
    }

    public static function make($key, $label, $isActive)
    {
        return new static($key, $label, $isActive);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

}