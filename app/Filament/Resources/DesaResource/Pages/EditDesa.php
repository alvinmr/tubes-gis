<?php

namespace App\Filament\Resources\DesaResource\Pages;

use App\Filament\Resources\DesaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesa extends EditRecord
{
    protected static string $resource = DesaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
