<?php

namespace Modules\App\Traits\Files;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;

trait HasFileUploads{

    #[Validate(['newImages.*' => 'image|max:2048'])] // Max 1MB per image
    public $photos = [];
    public bool $multiple = false;

    public $model, $path = 'images', $existingImages = [], $newImages = [];
    public string $inputId;


    public function updatedNewImages()
    {
        foreach ($this->newImages as $image) {
            $path = $image->store($this->path, 'public');
            $this->existingImages[] = $path;
        }

        $this->model->update(['images' => $this->existingImages]);
        $this->newImages = [];
    }

    public function removeImage($index)
    {
        $imagePath = $this->existingImages[$index] ?? null;

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
            unset($this->existingImages[$index]);
            $this->model->update(['images' => array_values($this->existingImages)]);
        }
    }

}
