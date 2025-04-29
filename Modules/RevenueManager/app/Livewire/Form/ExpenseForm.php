<?php

namespace Modules\RevenueManager\Livewire\Form;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Modules\App\Livewire\Components\Form\Button\ActionBarButton;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Traits\Form\Button\ActionBarButton as ActionBarButtonTrait;

class ExpenseForm extends LightWeightForm
{
    public function render()
    {
        return <<<'blade'
            <div>
                <h3>The <code>ExpenseForm</code> livewire component is loaded from the <code>RevenueManager</code> module.</h3>
            </div>
        blade;
    }
}
