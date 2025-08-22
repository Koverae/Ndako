<?php

namespace Modules\Admin\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;

class Overview extends Component
{
    #[Url(as: 'dash', keep: true)]
    public $dash = 'home';

    public function render()
    {
        return view('admin::livewire.overview')
        ->extends('admin::layouts.app');
    }

    public function changeDash($slug){
        return $this->dash = $slug;
    }
}
