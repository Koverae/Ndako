<?php

namespace Modules\ChannelManager\Models\Channel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\ChannelManager\Models\Channel\Channel;
use Modules\Properties\Models\Property\Property;

class SyncLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeIsChannel(Builder $query, $channel_id)
    {
        return $query->where('channel_id', $channel_id);
    }

    public function channel() {
        return $this->belongsToOne(Channel::class);
    }

    public function property() {
        return $this->belongsToOne(Property::class);
    }
}
