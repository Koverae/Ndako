<?php

namespace App\Models\Company;

use App\Models\Client\ApiClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Properties\Models\Property\Amenity;
use Modules\Settings\Models\System\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Properties\Models\Property\Feature;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    
    public static function boot() {
        parent::boot();

        static::created(function ($model) {
            $model->generateApiKeys($model);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('status', 'active');
    }

    public function isActive(Builder $builder) {
        return $builder->where('enabled', 1);
    }

    public function generateApiKeys($company)
    {
        $publicKey = 'pub_' . Str::random(32);
        $privateKey = 'priv_' . Str::random(64);

        $client = ApiClient::create([
            // 'uuid'=> Str::uuid(),
            'company_id' => $company->id,
            'name' => $company->name.' Access Keys',
            'public_key' => $publicKey,
            'private_key' => $privateKey,
            // 'private_key' => base64_decode(Crypt::encrypt($privateKey)), // Hash for secure storage
        ]);
        $client->save();
    }

    public function revokeApiKey($clientId)
    {
        $client = ApiClient::findOrFail($clientId);
        $client->update(['public_key' => null, 'private_key' => null]);

        return response()->json(['message' => 'API keys revoked successfully.']);
    }
    public function rotateKeys(Request $request)
    {
        $client = ApiClient::find($request->client_id);

        if (!$client) {
            return response()->json(['message' => 'Client not found.'], 404);
        }

        $client->api_key = Str::random(32);
        $client->api_secret = Str::random(64);
        $client->save();

        return response()->json([
            'message' => 'API keys rotated successfully!',
            'api_key' => $client->api_key,
            'api_secret' => $client->api_secret,
        ]);
    }

    /**
     * Get settings for the company.
     */
    public function setting()
    {
        return $this->hasOne(Setting::class, 'company_id', 'id');
    }
    
    /**
     * Get user for the company.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
    
    /**
     * Get languages for the company.
     */
    public function languages()
    {
        return $this->hasMany(Language::class, 'company_id', 'id');
    }
    
    /**
     * Get countries for the company.
     */
    public function countries()
    {
        return $this->hasMany(Country::class, 'company_id', 'id');
    }
    
    /**
     * Get amenities for the company.
     */
    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'company_id', 'id');
    }
    
    /**
     * Get features for the company.
     */
    public function features()
    {
        return $this->hasMany(Feature::class, 'company_id', 'id');
    }

}
