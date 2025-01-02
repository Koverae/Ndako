<?php

namespace Modules\App\Livewire\Components\Form\Template;

use Livewire\Component;
use Livewire\WithFileUploads;

abstract class LightWeightForm extends Component
{
    use WithFileUploads;

    public $photo, $image_path, $default_img = 'user', $status;
    public bool $checkboxes = false, $blocked = false, $has_avatar = false;

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

    public function tags() : array{
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
