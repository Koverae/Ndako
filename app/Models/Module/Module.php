<?php

namespace App\Models\Module;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team\Team;
use Illuminate\Database\Eloquent\Builder;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];


    public function scopeFindBySlug(Builder $query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeIsNotDefault(Builder $query)
    {
        return $query->where('is_default', false);
    }

    public function scopeIsEnabled(Builder $query)
    {
        return $query->where('enabled', true);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'installed_modules', 'module_slug', 'team_id');
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'installed_modules', 'module_slug', 'company_id');
    }

    public function uninstall(Company $company)
    {
        $company->modules()->detach($this->slug);
    }

    public function isInstalledBy(Company $company)
    {
        return InstalledModule::where('module_slug', $this->slug)
            ->where('company_id', $company->id)
            ->first();
    }

    public function installed_modules(){
        return $this->hasMany(InstalledModule::class, 'module_slug', 'slug');
    }

    public function module_users(){
        return $this->hasMany(ModuleUser::class, 'module_slug', 'slug');
    }

    // Parent App
    public function parent(){
        return $this->belongsTo(Module::class, 'parent_slug', 'slug');
    }

}
