<?php

namespace Modules\App\Livewire\Components\Form\Button;

class StatusBarButton{

    public string $component = 'app::form.button.status-bar.simple';

    public string $key;

    public string $label;

    public string $primary;

    public function __construct($key, $label, $primary)
    {
        $this->key = $key;
        $this->label = $label;
        $this->primary = $primary;
    }

    public static function make($key, $label, $primary)
    {
        return new static($key, $label, $primary);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }
}
