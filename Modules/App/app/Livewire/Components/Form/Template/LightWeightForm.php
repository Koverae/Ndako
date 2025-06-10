<?php

namespace Modules\App\Livewire\Components\Form\Template;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

abstract class LightWeightForm extends Component
{
    use WithFileUploads;

    public $photo, $image_path, $default_img = 'user', $status = null;
    public bool $checkboxes = false, $blocked = false, $has_avatar = false, $isInvoice = false, $hasPhoto = false;

    public $roomPrice = 0; // Price per night in KSh
    public $nights = 0;
    public $startDate = ''; // Booking start date
    public $endDate = ''; // Booking end date
    public $totalAmount = 0;
    public $dueAmount = 0;
    public $addingRowFor = null, $editingRowFor = null, $editRowId = null;
    public $newRow = [];
    public $editRow = [];


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

    public function statusBarButtons(){
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

    public function tables() : array{
        return [];
    }

    public function columns() : array{
        return [];
    }

    // Form Table
    public function addRow($tableKey)
    {
        return '';
    }

    public function cancelAddRow()
    {
        $this->addingRowFor = null;
        $this->newRow = [];
    }

    public function saveRow($tableKey)
    {
        return '';
    }

    public function edit(string $key, int $id){
        return '';
    }

    public function updateRow(string $key){
        return '';
    }

    public function cancelEdit(){
        return '';
    }
}
