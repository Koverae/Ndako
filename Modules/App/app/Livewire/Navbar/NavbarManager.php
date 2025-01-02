<?php

namespace Modules\App\Livewire\Navbar;

use Livewire\Component;
use Livewire\Attributes\Url;

class NavbarManager extends Component
{
    public $navbar;
    
    #[Url(keep: true)]
    public $menu;

    public function mount($menu = null){
        $navbarMapping = [
            1 => [
                'name' => 'App Manager',
                'path' => 'app::layouts.navbar-menu',
                'id' => 1,
                'slug' => 'app',
            ],
            2 => [
                'name' => 'Settings',
                'path' => 'settings::layouts.navbar-menu',
                'id' => 2,
                'slug' => 'settings'
            ],
            3 => [
                'name' => 'Properties',
                'path' => 'properties::layouts.navbar-menu',
                'id' => 3,
                'slug' => 'properties'
            ],
            4 => [
                'name' => 'Channel Manager',
                'path' => 'channelmanager::layouts.navbar-menu',
                'id' => 4,
                'slug' => 'channel-manager'
            ],
        ];

        // Set the navbar based on the menu variable
        if(session()->has('current_menu')){
            $menu = session('current_menu');
        }
        
        $this->menu = $menu;
        $this->navbar = $navbarMapping[$this->menu] ?? abort(404);
        $this->menu = $this->navbar['id'];
    }

    public function render()
    {
        // Check if the view exists before rendering it
        $viewPath = $this->navbar['path'];
        if (!view()->exists($viewPath)) {
            $viewPath = 'layouts.navbar-menu';
        }

        return view($viewPath);
    }
}
