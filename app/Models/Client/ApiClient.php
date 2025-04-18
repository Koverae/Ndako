<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    protected $fillable = ['company_id', 'name', 'public_key', 'private_key', 'authorized_domains'];

    protected $casts = [
        'authorized_domains' => 'array',
    ];


    public function isDomainAuthorized(string $domain): bool
    {
        return in_array($domain, $this->authorized_domains ?? []);
    }
}
