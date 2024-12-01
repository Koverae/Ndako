<?php

namespace Modules\App\Livewire\Components\Form;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
abstract class BaseForm extends Component
{
    public bool $blocked = false;


    public function render()
    {
        return view('app::livewire.components.form.base-form');
    }

    public function form(){
        return null;
    }

    public abstract function inputs() : array;

    public function tabs() : array{
        return [];
    }

    public function groups() : array{
        return [];
    }

    public function actionBarButtons() : array{
        return [];
    }

    public function statusBarButtons(){
        return [];
    }

    public function capsules(){
        return [];
    }

    public  function actionButtons() : array{
        return [];
    }

}
