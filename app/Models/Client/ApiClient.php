<?php

namespace App\Models\Client;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    // protected $connection = 'mysql';
    // protected $table = 'api_clients';
    protected $fillable = ['company_id', 'name', 'public_key', 'private_key', 'authorized_domains'];

    protected $casts = [
        'authorized_domains' => 'array',
    ];


    public function isDomainAuthorized(string $domain): bool
    {
        return in_array($domain, $this->authorized_domains ?? []);
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
