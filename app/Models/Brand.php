<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends Model
{
    use HasFactory;
    
    public static array $allowedFields = [
        'id',
        'name',
        'category_id',
        'sub_category_id',
        'country_id'
    ];
    public static array $allowedSorts = [
        'name',
        'created_at',
    ];
    public static array $allowedFilters = [
        'id',
        'name',
        'category_id',
        'sub_category_id',
        'country_id'
    ];

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function branch(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
    
    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * @return BelongsTo
     */
    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->BelongsTo(City::class);
    }

    
}
