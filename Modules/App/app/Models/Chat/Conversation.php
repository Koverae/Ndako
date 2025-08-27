<?php

namespace Modules\App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\App\Database\Factories\ConversationFactory;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['subject','status','created_by'];

    public function participants()   { return $this->hasMany(ConversationParticipant::class); }
    public function messages()       { return $this->hasMany(Message::class)->latest(); }

    public function scopeForUser($q, $userId)
    {
        return $q->whereHas('participants', fn($p) => $p->where('user_id',$userId));
    }

    public function unreadCountFor($userId): int
    {
        $participant = $this->participants->firstWhere('user_id', $userId);
        $lastRead = optional($participant)->last_read_at;

        return $this->messages()
            ->when($lastRead, fn($q) => $q->where('created_at','>', $lastRead))
            ->where('user_id','!=',$userId)
            ->count();
    }

    public function unreadCountForParticipant(string $type, int $id): int
    {
        $participant = $this->participants()
            ->where('participant_type',$type)
            ->where('participant_id',$id)
            ->first();

        $lastRead = optional($participant)->last_read_at;

        return $this->messages()
            ->when($lastRead, fn($q) => $q->where('created_at','>', $lastRead))
            ->where(function($q) use ($type,$id) {
                $q->where('sender_type','!=',$type)->orWhere('sender_id','!=',$id);
            })
            ->count();
    }

}
