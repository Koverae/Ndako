<?php

namespace Modules\App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ChannelManager\Models\Guest\Guest;

// use Modules\App\Database\Factories\Chat/ConversationParticipantFactory;

class ConversationParticipant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'conversation_id','participant_type','participant_id','role','last_read_at'
    ];
    protected $casts = ['last_read_at' => 'datetime'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function participant()  {

        $model = $this->getParticipantModel();
        return $model ? $this->belongsTo($model, 'participant_id') : null;
    }

    // helpers
    public function isUser(): bool  { return $this->participant_type === 'user'; }
    public function isGuest(): bool { return $this->participant_type === 'guest'; }


    protected function getParticipantModel()
    {
        if ($this->participant_type === 'user') {
            return User::class;
        }
        if ($this->participant_type === 'guest') {
            return Guest::class;
        }
        return User::class;
    }

}
