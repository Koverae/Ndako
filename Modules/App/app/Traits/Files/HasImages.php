<?php

namespace Modules\App\Traits\Files;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasImages{

    // protected function images(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => json_decode($value, true) ?: [], 
    //         set: fn ($value) => is_array($value) ? json_encode($value) : json_encode([]), // Ensure $value is an array before encoding
    //     );
    // }

    public function addImage($path)
    {
        $images = $this->images;
        $images[] = $path;
        $this->update(['images' => $images]);
    }

    public function removeImageAtIndex($index)
    {
        $images = $this->images;
        unset($images[$index]);
        $this->update(['images' => array_values($images)]);
    }

    public function firstImage(): ?string
    {
        // $images = json_decode($this->images, true); // Decode JSON to an array
        return $this->images[0] ?? null; // Return the first image or null
    }
}
