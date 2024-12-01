<?php

namespace Modules\App\Livewire\Components\Form\Template;

use Livewire\Component;

abstract class SimpleForm extends Component
{
    public bool $blocked = false;
    
    public function render()
    {
        return view('app::livewire.components.form.template.simple-form');
    }

    public function form(){
        return null;
    }

    public function inputs() : array{
        return [];
    }

    public function tabs() : array{
        return [];
    }

    public function groups() : array{
        return [];
    }

    public function actionBarButtons() : array{
        return [];
    }

    public function statusBarButtons() : array{
        return [];
    }

    public function capsules(){
        return [];
    }

    public function actionButtons() : array{
        return [];
    }
}
