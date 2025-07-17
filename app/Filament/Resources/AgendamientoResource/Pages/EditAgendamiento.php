<?php

namespace App\Filament\Resources\AgendamientoResource\Pages;

use App\Filament\Resources\AgendamientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendamiento extends EditRecord
{
    protected static string $resource = AgendamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
