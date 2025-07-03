<?php

namespace App\Filament\Agendar\Resources\SolicitudAdmisionResource\Pages;

use App\Filament\Agendar\Resources\SolicitudAdmisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudAdmision extends EditRecord
{
    protected static string $resource = SolicitudAdmisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
