<?php

namespace App\Filament\Resources\Shared\Schemas;

use App\Models\Address;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('receiver_name')
                            ->label('Receiver name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('receiver_phone')
                            ->label('Receiver phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Address details')
                    ->schema([
                        KeyValue::make('meta.address_details')
                            ->hiddenLabel()
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add row')
                            ->reorderable()
                            ->default([
                                'address1' => '',
                                'address2' => '',
                                'address3' => '',
                                'landmark' => '',
                            ])
                            ->afterStateHydrated(function (KeyValue $component, ?Address $record): void {
                                if (! $record) {
                                    return;
                                }

                                $details = data_get($record->meta, 'address_details');

                                if (filled($details)) {
                                    return;
                                }

                                if (blank($record->street)) {
                                    return;
                                }

                                $lines = array_values(array_filter(preg_split("/\r\n|\r|\n/", $record->street) ?: []));
                                $keys = ['address1', 'address2', 'address3', 'landmark'];

                                $hydrated = [];

                                foreach ($lines as $index => $line) {
                                    $hydrated[$keys[$index] ?? 'line'.($index + 1)] = $line;
                                }

                                $component->state($hydrated);
                            })
                            ->columnSpanFull(),
                    ]),

                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('country')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('state')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('city')
                            ->required()
                            ->maxLength(255),
                    ]),

                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->required()
                            ->step(0.0000001),

                        TextInput::make('longitude')
                            ->numeric()
                            ->required()
                            ->step(0.0000001),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareForSave(array $data): array
    {
        $meta = is_array($data['meta'] ?? null) ? $data['meta'] : [];
        $details = $meta['address_details'] ?? [];

        if (is_array($details)) {
            $details = collect($details)
                ->filter(fn ($value, $key): bool => filled($key) && filled($value))
                ->all();

            $meta['address_details'] = $details;

            $data['street'] = collect($details)->values()->implode("\n");
        }

        $data['meta'] = $meta;

        return $data;
    }
}
