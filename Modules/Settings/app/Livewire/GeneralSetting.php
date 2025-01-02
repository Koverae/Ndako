<?php

namespace Modules\Settings\Livewire;

use Livewire\Component;
use App\Models\Team\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class GeneralSetting extends Component
{
    #[Url(as: 'view', keep: true)]
    public $view = 'general';

    public function mount($view = null){
        if($view){
            $this->view = $view;
        }
    }

    public function render()
    {
        // $team = Team::where('id', Auth::user()->team->id)->where('uuid', Auth::user()->team->uuid)->first();

        return view('settings::livewire.general-setting')
        ->extends('layouts.app');
    }

    public function changePanel($panel){
        return $this->view = $panel;
    }
}
