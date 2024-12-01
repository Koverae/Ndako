<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstalledModule extends Model
{
    use HasFactory;

    // protected $table = 'installed_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_slug', 'slug');
    }

}
