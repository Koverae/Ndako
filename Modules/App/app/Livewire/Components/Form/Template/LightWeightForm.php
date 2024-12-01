<?php

namespace Modules\App\Livewire\Components\Form\Template;

use Livewire\Component;

abstract class LightWeightForm extends Component
{
    public bool $blocked = false;
    public function render()
    {
        return view('app::livewire.components.form.template.light-weight-form');
    }

    public function form(){
        return null;
    }

    public function actionBarButtons() : array{
        return [];
    }

    public function inputs() : array{
        return [];
    }

    // Provide a default implementation that returns an empty array.
    public function groups(): array {
        return [];
    }

    public function capsules() : array{
        return [];
    }
}
