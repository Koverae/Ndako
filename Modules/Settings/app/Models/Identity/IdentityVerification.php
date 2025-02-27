<?php

namespace Modules\Settings\Models\Identity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\Identity/IdentityVerificationFactory;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_path',
        'selfie_path',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
