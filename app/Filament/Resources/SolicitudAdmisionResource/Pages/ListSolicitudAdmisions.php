<?php

namespace App\Filament\Resources\SolicitudAdmisionResource\Pages;

use App\Filament\Resources\SolicitudAdmisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSolicitudAdmisions extends ListRecords
{
    protected static string $resource = SolicitudAdmisionResource::class;

      protected function getHeaderActions(): array
    {
        // Devuelve un array vacío para eliminar el botón de creación
        return [];
    }
}
