<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaskesResource\Pages;
use App\Filament\Resources\FaskesResource\RelationManagers;
use App\Models\Faskes;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaskesResource extends Resource
{
    protected static ?string $model = Faskes::class;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('nama')
                        ->label('Nama Faskes')
                        ->required(),
                    Textarea::make('alamat')
                        ->label('Alamat')
                        ->required(),
                    FileUpload::make('images')
                        ->label('Images')
                        ->multiple()
                        ->directory('faskes')
                        ->required(),
                    Select::make('id_tipe')
                        ->relationship('type', 'name'),
                    OSMMap::make('coordinate')
                        ->label('Location')
                        ->draggable()
                        ->showZoomControl()
                        ->showMarker()
                        ->zoom(15)
                        ->afterStateHydrated(function ($state, callable $set) {
                            if(is_array($state)){ 
                                $set('coordinate', ['lat' => $state[0] ?? -8.536331109906179, 'lng' => $state[1] ?? 115.2098746597767]);
                            }
                        })
                        ->mutateDehydratedStateUsing(function ($state) {
                            return [$state['lat'], $state['lng']];
                        })
                        // ->afterStateUpdated(function ($state, callable $set) {
                        //     if (is_array($state)) {
                        //         $set('coordinate', ['lat' => $state[0] ?? 0, 'lng' => $state[1] ?? 0]);
                        //     }
                        // })
                        

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alamat')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FasilitasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaskes::route('/'),
            'create' => Pages\CreateFaskes::route('/create'),
            'edit' => Pages\EditFaskes::route('/{record}/edit'),
        ];
    }
}
