<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryGroup extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'sr',
        'featured',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'sr' => 'integer',
            'featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_group_items');
    }
}
