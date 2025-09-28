<?php

namespace Modules\App\Transformers\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'conversation_id' => (int)$this->conversation_id,
            'sender_type' => $this->sender_type,
            'sender_id' => (int)$this->sender_id,
            'body' => $this->body,
            'attachments' => $this->attachments?->map(fn($a) => [
            'id' => (int)$a->id,
            'url' => $a->path ? asset('storage/' . $a->path) : null,
            'name' => $a->original_name,
            'type' => $a->mime_type,
            'size' => (int)$a->size,
            ]) ?? [],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
