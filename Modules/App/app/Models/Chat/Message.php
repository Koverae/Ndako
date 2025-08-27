<?php

namespace Modules\App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ChannelManager\Models\Guest\Guest;

// use Modules\App\Database\Factories\Chat/MessageFactory;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['conversation_id','sender_type','sender_id','type','body'];

    public function conversation() { return $this->belongsTo(Conversation::class); }

    public function sender()
    {
        $model = $this->getSenderModel();
        return $model ? $this->belongsTo($model, 'sender_id') : null;
    }

    protected function getSenderModel()
    {
        if ($this->sender_type === 'user') {
            return User::class;
        }
        if ($this->sender_type === 'guest') {
            return Guest::class;
        }
        return User::class;
    }

    public function attachments()  { return $this->hasMany(MessageAttachment::class); }
}
