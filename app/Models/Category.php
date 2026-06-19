<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'featured',
        'is_visible',
        'sr',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'is_visible' => 'boolean',
            'sr' => 'integer',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }

    public function categoryGroups(): BelongsToMany
    {
        return $this->belongsToMany(CategoryGroup::class, 'category_group_items');
    }
}
