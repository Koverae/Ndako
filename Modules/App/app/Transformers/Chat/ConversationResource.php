<?php

namespace Modules\App\Transformers\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $actorType = 'user';
        $actorId = optional($request->user())->id;


        $last = $this->messages()->latest()->first();
        $other = $this->participants->first(function ($p) use ($actorType, $actorId) {
            return !($p->participant_type === $actorType && (int)$p->participant_id === (int)$actorId);
        });


        $unread = method_exists($this->resource, 'unreadCountForParticipant')
        ? $this->unreadCountForParticipant($actorType, (int)$actorId)
        : 0;


        return [
            'id' => (int)$this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'other' => $other ? [
                'type' => $other->participant_type,
                'id' => (int)$other->participant_id,
                'name' => $other->participant?->name ?? ($other->participant_type === 'guest' ? 'Guest' : 'User'),
                'avatar' => $other->participant->avatar ?? null,
            ] : null,
                'last' => $last ? [
                'id' => (int)$last->id,
                'body' => $last->body,
                'sender_type' => $last->sender_type,
                'sender_id' => (int)$last->sender_id,
                'created_at' => $last->created_at?->toIso8601String(),
            ] : null,
            'unread' => $unread,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

}
