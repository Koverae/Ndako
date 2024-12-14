<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Settings\Models\System\Setting;
use App\Models\User;
use Modules\Settings\Models\Language\Language;

class Company extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    

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

}
