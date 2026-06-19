<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Support\Str;

trait MutatesSlugFromName
{
    protected function applySlugFromName(array $data): array
    {
        $data['slug'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
