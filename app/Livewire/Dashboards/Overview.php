<?php

namespace App\Livewire\Dashboards;

use Livewire\Attributes\Url;
use Livewire\Component;

class Overview extends Component
{
    #[Url(as: 'dash', keep: true)]
    public $dash = 'home';
    public function render()
    {
        return view('livewire.dashboards.overview')
        ->extends('layouts.app');
    }

    public function changeDash($slug){
        return $this->dash = $slug;
    }
}
