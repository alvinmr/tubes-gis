<?php

namespace App\Filament\Resources\DesaResource\RelationManagers;

use App\Forms\Components\MapInput;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KasusCovidRelationManager extends RelationManager
{
    protected static string $relationship = 'kasusCovid';

    protected static ?string $recordTitleAttribute = 'keterangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('suspek')
                    ->label('Jumlah Suspek')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('dirawat')
                    ->label('Jumlah Dirawat')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('sembuh')
                    ->label('Jumlah Sembuh')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('meninggal')
                    ->label('Jumlah Meninggal')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpan(2)
                    ->label('Keterangan')
                    ->afterStateHydrated(function ($state, callable $set) {
                        if (is_array($state)) {
                        }
                    })
                    ->required(),
                MapInput::make('coordinate')
                    ->columnSpan(2)
                    ->showMarker()
                    ->draggable()
                    ->afterStateHydrated(function (MapInput $component, $state, callable $set, RelationManager $livewire) {
                        if (is_array($state)) {
                            $set('coordinate', ['lat' => $state[0] ?? -8.536331109906179, 'lng' => $state[1] ?? 115.2098746597767]);
                        }
                        $component->geojson(asset($livewire->ownerRecord->geojson));
                    })
                    ->mutateDehydratedStateUsing(function ($state) {
                        return [$state['lat'], $state['lng']];
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('suspek')
                    ->label('Jumlah Suspek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dirawat')
                    ->label('Jumlah Dirawat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sembuh')
                    ->label('Jumlah Sembuh')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('meninggal')
                    ->label('Jumlah Meninggal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DissociateAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DissociateBulkAction::make(),
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
