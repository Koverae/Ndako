<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    protected $fillable = ['company_id', 'name', 'public_key', 'private_key'];
}
