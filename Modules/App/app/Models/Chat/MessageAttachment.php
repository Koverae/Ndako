<?php

namespace Modules\App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

// use Modules\App\Database\Factories\Chat/MessageAttachmentFactory;

class MessageAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['message_id','path','original_name','mime_type','size'];

    public function message() { return $this->belongsTo(Message::class); }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
