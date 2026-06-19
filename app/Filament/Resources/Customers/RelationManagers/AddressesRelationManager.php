<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Shared\Schemas\AddressForm;
use App\Models\Address;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $title = 'Addresses';

    public function form(Schema $schema): Schema
    {
        return AddressForm::configure($schema);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('receiver_name')->label('Receiver name'),
                        TextEntry::make('receiver_phone')->label('Receiver phone'),
                    ]),

                Section::make('Address details')
                    ->schema([
                        KeyValueEntry::make('meta.address_details')
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ]),

                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('country'),
                        TextEntry::make('state'),
                        TextEntry::make('city'),
                    ]),

                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('latitude'),
                        TextEntry::make('longitude'),
                    ]),

                TextEntry::make('distance_to_shop')
                    ->label('Distance to shop (Km)')
                    ->formatStateUsing(fn (?float $state): string => filled($state)
                        ? number_format($state, 2).' Km'
                        : '—'),

                TextEntry::make('updated_at')
                    ->label('Updated at')
                    ->dateTime('d F Y h:i:a'),
            ]);
    }

    protected function configureAddressAction(CreateAction | EditAction $action): CreateAction | EditAction
    {
        return $action
            ->mutateFormDataUsing(fn (array $data): array => AddressForm::prepareForSave($data))
            ->modalFooterActionsAlignment(Alignment::Start);
    }

    protected function isViewPage(): bool
    {
        return is_a($this->pageClass, ViewCustomer::class, true);
    }

    protected function isEditPage(): bool
    {
        return is_a($this->pageClass, EditCustomer::class, true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('receiver_name')
                    ->label('Receiver name')
                    ->searchable(),

                TextColumn::make('receiver_phone')
                    ->label('Receiver phone')
                    ->searchable(),

                TextColumn::make('distance_to_shop')
                    ->label('Distance to Shop (Km)')
                    ->getStateUsing(fn (Address $record): ?float => $record->distance_to_shop)
                    ->formatStateUsing(fn (?float $state): string => filled($state)
                        ? number_format($state, 2).' Km'
                        : '—')
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->dateTime('d F Y h:i:a')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->searchable()
            ->headerActions([
                $this->configureAddressAction(CreateAction::make())
                    ->visible(fn (): bool => $this->isEditPage()),

                AttachAction::make()
                    ->preloadRecordSelect()
                    ->visible(fn (): bool => $this->isEditPage()),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalFooterActionsAlignment(Alignment::Start)
                    ->visible(fn (): bool => $this->isViewPage()),

                ActionGroup::make([
                    ViewAction::make()
                        ->modalFooterActionsAlignment(Alignment::Start),
                    $this->configureAddressAction(EditAction::make()),
                    DetachAction::make(),
                    DeleteAction::make(),
                ])
                    ->tooltip('Actions')
                    ->visible(fn (): bool => $this->isEditPage()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ])
                    ->visible(fn (): bool => $this->isEditPage()),
            ])
            ->emptyStateHeading('No addresses yet')
            ->emptyStateDescription('Add a delivery address for this customer.');
    }
}
