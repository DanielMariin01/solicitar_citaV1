<?php

namespace App\Filament\Resources\EPSResource\Pages;

use App\Filament\Resources\EPSResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEPS extends EditRecord
{
    protected static string $resource = EPSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
