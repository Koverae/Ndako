<?php

namespace Modules\App\Livewire\Components\Wizard;

use Livewire\Component;

class SimpleWizard extends Component
{
    public int $currentStep = 0;

    public function goToNextStep()
    {
        // $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function goToPreviousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }
    }

    public function validateCurrentStep()
    {
        $steps = $this->steps();
        $currentStep = $steps[$this->currentStep] ?? null;

        if ($currentStep && method_exists($currentStep, 'rules')) {
            $this->validate($currentStep->rules());
        }
    }

    public function render()
    {
        return view('app::livewire.components.wizard.simple-wizard');
    }

    public function steps(){
        return [];
    }

    public function stepPages(){
        return [];
    }
}
