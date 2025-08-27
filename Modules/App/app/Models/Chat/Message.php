<?php

namespace Modules\App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\App\Database\Factories\Chat/MessageFactory;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['conversation_id','sender_type','sender_id','type','body'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender()       { return $this->morphTo(__FUNCTION__, 'sender_type','sender_id'); }
    public function attachments()  { return $this->hasMany(MessageAttachment::class); }
}
