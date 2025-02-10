<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends Model
{
    use HasFactory;

    public static array $allowedFields = [
        'name',
        'category_id',
        'id',
    ];
    public static array $allowedSorts = [
        'name',
        'created_at',
        'category_id',
        'id',
    ];
    public static array $allowedFilters = [
        'name',
        'category_id',
        'id',
    ];

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
