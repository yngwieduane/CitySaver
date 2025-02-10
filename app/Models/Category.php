<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public static array $allowedFields = [
        'name',
        'id',
    ];
    public static array $allowedSorts = [
        'name',
        'created_at',
        'id',
    ];
    public static array $allowedFilters = [
        'name',
        'id',
    ];


    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function subcategory(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * @return HasMany
     */
    public function brand(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
    
}
