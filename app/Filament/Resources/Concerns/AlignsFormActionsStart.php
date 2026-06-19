<?php

namespace App\Filament\Resources\Concerns;

use Filament\Support\Enums\Alignment;

trait AlignsFormActionsStart
{
    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::Start;
    }
}
