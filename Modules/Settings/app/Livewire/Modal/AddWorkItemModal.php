<?php

namespace Modules\Settings\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Settings\Models\WorkItem;

class AddWorkItemModal extends ModalComponent
{
    use WithFileUploads;
    public WorkItem $task;
    public $title, $room, $description, $type, $priority, $assignedTo, $reportedBy;
    public $rooms;

    public function mount($task = null){
        if($task){
            $this->task = $task;
            $this->title = $task->title;
            $this->room = $task->room_id;
            $this->description = $task->description;
            $this->type = $task->type;
            $this->priority = $task->priority;
            $this->assignedTo = $task->assigned_to;
            $this->reportedBy = $task->created_by;
        }
        $this->rooms = PropertyUnit::isCompany(current_company()->id)->get();
    }

    public function render()
    {
        return view('settings::livewire.modal.add-work-item-modal');
    }

    public function addWorkItem(){

        $work = WorkItem::create([
            'company_id' => current_company()->id,
            'room_id' => $this->room,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'status' => 'pending',
            'priority' => $this->priority,
            'assigned_to' => $this->assignedTo,
            'created_by' => auth()->user()->id,
        ]);
        $work->save();

        $this->dispatch('load-work-items');

        $this->closeModal();
    }
}
