<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxGroup extends Model
{
    protected $fillable = [
        'name',
        'notes',
        'hsn',
    ];

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'tax_group_items');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
