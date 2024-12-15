<?php

namespace Modules\App\Livewire\Components\Form\Template;

use Livewire\Component;
use Livewire\WithFileUploads;

abstract class SimpleAvatarForm extends Component
{
    use WithFileUploads;
    
    public $photo, $image_path, $default_img = 'user', $status;
    public bool $checkboxes = false, $blocked = false, $has_avatar = false;

    public function render()
    {
        return view('app::livewire.components.form.template.simple-avatar-form');
    }

    public function form(){
        return null;
    }

    public function updateForm() {
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

    public function tables() : array{
        return [];
    }

    public function columns() : array{
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
