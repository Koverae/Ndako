<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Properties\Models\Property\Amenity;
use Modules\Settings\Models\System\Setting;
use App\Models\User;
use Modules\Properties\Models\Property\Feature;
use Modules\Settings\Models\Language\Language;
use Modules\Settings\Models\Localization\Country;

class Company extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    
    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('status', 'active');
    }

    public function isActive(Builder $builder) {
        return $builder->where('enabled', 1);
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
