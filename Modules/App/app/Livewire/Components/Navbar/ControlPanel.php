<?php

namespace Modules\App\Livewire\Components\Navbar;

use Illuminate\Support\Arr;
use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

abstract class ControlPanel extends Component
{
    #[Url(as: 'view_type', keep: true)]
    public $view_type = 'lists';

    public $search = '';
    public bool $change = false, $showBreadcrumbs = true, $showCreateButton = true, $showPagination = false, $showIndicators= false, $isForm = false;

    // Configurable options
    public $separator = '/', $urlPrefix = '', $currentPage, $new, $add, $event, $createButtonLabel = 'Nouveau';

    public array $breadcrumbs = [];

    // public $view_type = 'lists';

    public function mount(){
        $this->view_type = $this->view;
    }

    public function render()
    {
        return view('app::livewire.components.navbar.control-panel');
    }

    public function switchButtons() : array{
        return [];
    }


    public function generateBreadcrumbs()
    {
        $segments = request()->segments();

        foreach ($segments as $key => $segment) {
            $url = implode('/', array_slice($segments, 0, $key + 1));

            // Prefix the URL if specified
            $url = $this->urlPrefix ? $this->urlPrefix . '/' . $url : $url;

            $this->breadcrumbs[] = [
                'url' => url($url),
                'label' => ucwords(str_replace(['-', '_'], ' ', $segment)),
            ];
        }
    }


    public function updatedSearch($value)
    {
        // Update guests based on search term
        $this->dispatch('update-search', search: $this->search);
    }

    
    #[On('change')]
    public function changeDetected(){
        $this->change = true;
    }

    public function saveUpdate(){
        $this->dispatch($this->event);
        // $this->dispatch('saveChange');
    }

    public function resetForm(){
        $this->dispatch('reset-form');
    }

    public  function actionButtons() : array{
        return [];
    }

    public function switchView($view){
        $this->dispatch('switch-view', view: $view);
        return $this->view_type = $view;
    }

}
