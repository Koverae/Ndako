<?php

namespace Modules\App\Livewire\Components\Wizard;

class StepPage{

    public string $component = 'app::wizard.step-page.simple';

    public string $key, $label, $step;

    public function __construct($key, $label, $step)
    {
        $this->key = $key;
        $this->label = $label;
        $this->step = $step;
    }

    public static function make($key, $label, $step)
    {
        return new static($key, $label, $step);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

}