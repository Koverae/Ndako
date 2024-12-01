<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ModuleCategory extends Model
{
    use HasFactory;

    protected $guarded = [''];


    public function scopeIsApp(Builder $query) {
        return $query->where('type', 'app_category');
    }
    public function scopeIsIndustry(Builder $builder) {
        return $builder->where('type', 'industry_category');
    }

    // Modules
    public function modules(){
        return $this->hasMany(Module::class, 'module_category_id', 'id');
    }
}
