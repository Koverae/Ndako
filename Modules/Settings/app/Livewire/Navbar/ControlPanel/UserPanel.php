<?php

namespace Modules\Settings\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class UserPanel extends ControlPanel
{
    public $user;

    public function mount($user = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->filterTypes = [
            'status' => [
                'confirmed' => 'Active',    // string filter
                'never-connected' => 'Inactive',
            ],
        ];

        $this->new = route('settings.users.create');
        if($user){
            $this->showIndicators = true;
            $this->user = $user;
            $this->isForm = true;
            $this->currentPage = $user->name;
        }else{
            $this->currentPage = "Users";
        }
    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
            SwitchButton::make('map',"switchView('map')", icon: "bi-map"),
            // SwitchButton::make('delivery_lead_time',"Delais de livraison", ''),
        ];
    }

    public function actionButtons() : array
    {
        return [
            ActionButton::make('archive', '<i class="bi bi-inboxes"></i> '.__('Archive'), 'archiveUser()'),
            ActionButton::make('duplicate', __('<i class="fa-regular fa-copy"></i> Duplicate'), 'duplicateUser()'),
            ActionButton::make('delete', '<i class="bi bi-trash"></i> '.__('Delete'), 'deleteQT()', true),
            ActionButton::make('change-password', __('Change Password'), "changePasswordUser()", false, 'sent'),
            ActionButton::make('disable-2fa', __('Disable two-factor authentification'), "disable2Fa()", false, 'sent'),
            // Add more buttons as needed
        ];
    }

    public function archiveUser(){
        $this->dispatch('archive-user');
    }

    public function duplicateUser(){
        $this->dispatch('duplicate-user');
    }

    public function deleteUser(){
        $this->dispatch('delete-user');
    }

    public function changePasswordUser(){
        $this->dispatch('change-pwd-user');
    }

    public function disable2Fa(){
        $this->dispatch('disable-2fa');
    }
}
