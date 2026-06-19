<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'title',
        'receiver_name',
        'receiver_phone',
        'street',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'description',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'meta' => 'array',
        ];
    }

    public function getDistanceToShopAttribute(): ?float
    {
        $value = data_get($this->meta, 'distance_to_shop_km');

        return filled($value) ? (float) $value : null;
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_addresses');
    }
}
